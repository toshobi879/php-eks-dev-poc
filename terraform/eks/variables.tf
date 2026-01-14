variable "aws_region" {
  type = string
}

variable "project_name" {
  type = string
}


variable "eks_cluster_version" {
  type = string
}

variable "node_instance_type" {
  type = string
}

variable "desired_capacity" {
  type = number
}
