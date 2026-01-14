variable "aws_region" {
  type = string
}

variable "backend_bucket_name" {
  type = string
}

variable "dynamodb_table_name" {
  type = string
}

variable "github_actions_role_name" {
  description = "Name of the IAM role used by GitHub Actions (OIDC)"
  type        = string
}
