terraform {
  backend "s3" {
    bucket         = "caltrans-eks-terraform-state-bucket"
    key            = "rds/php-poc/terraform.tfstate"
    region         = "us-west-2"
    dynamodb_table = "caltrans-eks-terraform-lock"
    encrypt        = true
  }
}
