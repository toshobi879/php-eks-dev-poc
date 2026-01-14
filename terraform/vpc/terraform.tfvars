aws_region  = "us-west-2"
project_name = "caltrans-eks-php"
environment  = "prod"

vpc_cidr = "10.20.0.0/16"

azs = ["us-west-2a", "us-west-2b"]

private_subnets = ["10.20.1.0/24", "10.20.2.0/24"]
public_subnets  = ["10.20.11.0/24", "10.20.12.0/24"]
