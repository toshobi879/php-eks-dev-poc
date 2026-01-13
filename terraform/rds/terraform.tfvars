aws_region   = "us-west-2"
project_name = "php-eks-rds-poc"

vpc_id = "vpc-0c07f6b18652eda85"

private_subnet_ids = [
  "subnet-02cd9f860cf61acb8",
  "subnet-079c9958bfca9c53a"
]

eks_cluster_sg_id = "sg-0aa1bf1ae365bd0a7"

db_name     = "appdb"
db_username = "appuser"
db_password = "ChangeMe_StrongPassword_123!"
