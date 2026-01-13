variable "aws_region" {
  description = "AWS region to deploy EKS"
  type        = string
}

variable "project_name" {
  description = "Prefix for all resources"
  type        = string
}

variable "eks_cluster_version" {
  description = "EKS Kubernetes version"
  type        = string
}

variable "node_instance_type" {
  description = "Instance type for worker nodes"
  type        = string
}

variable "desired_capacity" {
  description = "Desired number of worker nodes"
  type        = number
}
