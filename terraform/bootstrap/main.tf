############################################
# S3 Bucket for Terraform State
############################################

resource "aws_s3_bucket" "terraform_state" {
  bucket = var.backend_bucket_name

  lifecycle {
    prevent_destroy = true
  }

  tags = {
    Name        = var.backend_bucket_name
    Environment = "prod"
    Purpose     = "terraform-backend"
  }
}

############################################
# Enable Versioning (Production Mandatory)
############################################

resource "aws_s3_bucket_versioning" "versioning" {
  bucket = aws_s3_bucket.terraform_state.id

  versioning_configuration {
    status = "Enabled"
  }
}

############################################
# Enable Encryption (Production Mandatory)
############################################

resource "aws_s3_bucket_server_side_encryption_configuration" "encryption" {
  bucket = aws_s3_bucket.terraform_state.id

  rule {
    apply_server_side_encryption_by_default {
      sse_algorithm = "AES256"
    }
  }
}

############################################
# DynamoDB Table for State Locking
############################################

resource "aws_dynamodb_table" "terraform_locks" {
  name         = var.dynamodb_table_name
  billing_mode = "PAY_PER_REQUEST"
  hash_key     = "LockID"

  attribute {
    name = "LockID"
    type = "S"
  }

  point_in_time_recovery {
    enabled = true
  }

  tags = {
    Name        = var.dynamodb_table_name
    Environment = "prod"
    Purpose     = "terraform-locking"
  }
}
