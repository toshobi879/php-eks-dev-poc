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

variable "github_actions_role_arn" {
  description = "IAM role ARN used by GitHub Actions"
  type        = string
}

variable "sso_admin_role_arn" {
  description = "SSO admin role ARN for human access"
  type        = string
}

variable "eks_admin_policy_arn" {
  description = "EKS cluster admin policy ARN"
  type        = string
  default     = "arn:aws:eks::aws:cluster-access-policy/AmazonEKSClusterAdminPolicy"
}
