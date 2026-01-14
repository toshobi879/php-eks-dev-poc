data "terraform_remote_state" "vpc" {
  backend = "s3"
  config = {
    bucket = "billow-poc-terraform-state"
    key    = "vpc/terraform.tfstate"
    region = "us-west-2"
  }
}

data "terraform_remote_state" "eks" {
  backend = "s3"
  config = {
    bucket = "billow-poc-terraform-state"
    key    = "eks/terraform.tfstate"
    region = "us-west-2"
  }
}

resource "aws_db_subnet_group" "this" {
  name       = "${var.project_name}-dbsubnets"
  subnet_ids = data.terraform_remote_state.vpc.outputs.private_subnets
}

resource "aws_security_group" "rds_sg" {
  name   = "${var.project_name}-rds-sg"
  vpc_id = data.terraform_remote_state.vpc.outputs.vpc_id

  ingress {
    from_port       = 3306
    to_port         = 3306
    protocol        = "tcp"
    security_groups = [data.terraform_remote_state.eks.outputs.cluster_security_group_id]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "aws_db_instance" "mysql" {
  identifier              = "${var.project_name}-mysql"
  engine                  = "mysql"
  engine_version          = "8.0"
  instance_class          = var.instance_class
  allocated_storage       = var.allocated_storage

  db_name  = var.db_name
  username = var.db_username
  password = var.db_password

  db_subnet_group_name    = aws_db_subnet_group.this.name
  vpc_security_group_ids  = [aws_security_group.rds_sg.id]

  publicly_accessible     = false
  backup_retention_period = 7
  deletion_protection     = true
  skip_final_snapshot     = false
}
