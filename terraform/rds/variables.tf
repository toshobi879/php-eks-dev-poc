variable "aws_region" {
  type = string
}

variable "project_name" {
  type    = string
  default = "php-eks-rds-poc"
}

variable "vpc_id" {
  type = string
}

variable "private_subnet_ids" {
  type = list(string)
}

# Use EKS cluster security group id here
variable "eks_cluster_sg_id" {
  type = string
}

variable "db_name" {
  type    = string
  default = "appdb"
}

variable "db_username" {
  type    = string
  default = "appuser"
}

variable "db_password" {
  type      = string
  sensitive = true
}
