terraform {
  backend "s3" {
    bucket         = "billow-poc-terraform-state"
    key            = "rds/terraform.tfstate"
    region         = "us-west-2"
    dynamodb_table = "terraform-locks"
    encrypt        = true
  }
}
