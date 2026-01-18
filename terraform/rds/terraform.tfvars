aws_region = "us-west-2"

project_name = "caltrans-eks-php"

db_name     = "appdb"
db_username = "appuser"

instance_class    = "db.t3.micro"
allocated_storage = 20

backup_retention_period = 0
deletion_protection     = false
skip_final_snapshot     = true
