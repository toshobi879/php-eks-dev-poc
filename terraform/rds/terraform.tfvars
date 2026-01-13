aws_region   = "us-west-2"
project_name = "php-eks-rds-poc"

vpc_id = "vpc-091a2616296b13881"

private_subnet_ids = [
  "subnet-0c2d20de79a167342",
  "subnet-0597b4f8b3175225c"
]

eks_cluster_sg_id = "sg-0327f16eb1cae39dc"

db_name     = "appdb"
db_username = "appuser"
db_password = "ChangeMe_StrongPassword_123!"
