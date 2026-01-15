data "terraform_remote_state" "vpc" {
  backend = "s3"
  config = {
    bucket = "billow-poc-terraform-state"
    key    = "vpc/terraform.tfstate"
    region = "us-west-2"
  }
}

module "eks" {
  source  = "terraform-aws-modules/eks/aws"
  version = "~> 20.11"

  cluster_name    = "${var.project_name}-cluster"
  cluster_version = var.eks_cluster_version

  vpc_id     = data.terraform_remote_state.vpc.outputs.vpc_id
  subnet_ids = data.terraform_remote_state.vpc.outputs.private_subnets

  cluster_endpoint_public_access = true

  ############################################
  # ✅ EKS ACCESS ENTRIES (PRODUCTION CORRECT)
  ############################################
  access_entries = {

    eks_admin_sso = {
      principal_arn = var.sso_admin_role_arn

      policy_associations = {
        cluster_admin = {
          policy_arn = var.eks_admin_policy_arn
          access_scope = {
            type = "cluster"
          }
        }
      }
    }

    github_actions = {
      principal_arn = var.github_actions_role_arn

      policy_associations = {
        cluster_admin = {
          policy_arn = var.eks_admin_policy_arn
          access_scope = {
            type = "cluster"
          }
        }
      }
    }
  }
  ############################################

  eks_managed_node_groups = {
    default = {
      desired_size   = var.desired_capacity
      max_size       = var.desired_capacity + 1
      min_size       = 1
      instance_types = [var.node_instance_type]
    }
  }

  tags = {
    Project = var.project_name
  }
}
