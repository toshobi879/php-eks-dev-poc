variable "aws_region" {
  description = "AWS region to deploy EKS"
  type        = string
  default     = "us-west-2"
}

variable "project_name" {
  description = "Prefix for all resources"
  type        = string
  default     = "caltrans-eks-dev-poc"
}

variable "eks_cluster_version" {
  description = "EKS Kubernetes version"
  type        = string
  default     = "1.28"
}

variable "node_instance_type" {
  description = "Instance type for worker nodes"
  type        = string
  default     = "t3.medium"
}

variable "desired_capacity" {
  description = "Desired number of worker nodes"
  type        = number
  default     = 2
}
