variable "aws_region" {
  description = "AWS region where backend resources will be created"
  type        = string
}

variable "backend_bucket_name" {
  description = "Name of the S3 bucket to store Terraform state"
  type        = string
}

variable "dynamodb_table_name" {
  description = "Name of the DynamoDB table for Terraform state locking"
  type        = string
}

variable "github_actions_role_name" {
  description = "Name of the IAM role used by GitHub Actions (OIDC)"
  type        = string
}

