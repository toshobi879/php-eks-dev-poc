############################################
# VPC for EKS (using official VPC module)
############################################

module "vpc" {
  source  = "terraform-aws-modules/vpc/aws"
  version = "~> 5.0"

  name = "${var.project_name}-vpc"
  cidr = "10.20.0.0/16"

  azs             = ["${var.aws_region}a", "${var.aws_region}b"]
  private_subnets = ["10.20.1.0/24", "10.20.2.0/24"]
  public_subnets  = ["10.20.11.0/24", "10.20.12.0/24"]

  enable_nat_gateway = true
  single_nat_gateway = true

  enable_dns_hostnames = true
  enable_dns_support   = true

  tags = {
    Project     = var.project_name
    Environment = "poc"
  }
}

############################################
# EKS Cluster (using official EKS module)
############################################

module "eks" {
  source  = "terraform-aws-modules/eks/aws"
  version = "~> 20.0"

  cluster_name    = "${var.project_name}-cluster"
  cluster_version = var.eks_cluster_version

  vpc_id     = module.vpc.vpc_id
  subnet_ids = module.vpc.private_subnets

  cluster_endpoint_public_access = true

  ############################################
  # ✅ AUTOMATED EKS ACCESS (NO aws-auth)
  ############################################
  access_entries = {

    ##########################################
    # SSO Admin (Human access)
    ##########################################
    eks_admin_sso = {
      principal_arn = "arn:aws:iam::020408743573:role/aws-reserved/sso.amazonaws.com/us-west-2/AWSReservedSSO_AWSAdministratorAccess_3fed78f1fb50999a"

      policy_associations = {
        cluster_admin = {
          policy_arn = "arn:aws:eks::aws:cluster-access-policy/AmazonEKSClusterAdminPolicy"
          access_scope = {
            type = "cluster"
          }
        }
      }
    }

    ##########################################
    # GitHub Actions (CI/CD automation)
    ##########################################
    github_actions = {
      principal_arn = "arn:aws:iam::020408743573:role/php-eks-dev-poc-role"

      policy_associations = {
        cluster_admin = {
          policy_arn = "arn:aws:eks::aws:cluster-access-policy/AmazonEKSClusterAdminPolicy"
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

      tags = {
        Name = "${var.project_name}-node"
      }
    }
  }

  cluster_tags = {
    Project     = var.project_name
    Environment = "poc"
  }

  tags = {
    Project     = var.project_name
    Environment = "poc"
  }
}
