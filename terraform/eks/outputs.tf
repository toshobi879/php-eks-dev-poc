output "cluster_name" {
  description = "EKS cluster name"
  value       = module.eks.cluster_name
}

output "cluster_endpoint" {
  description = "EKS cluster API server endpoint"
  value       = module.eks.cluster_endpoint
}

output "cluster_security_group_id" {
  description = "EKS cluster security group ID"
  value       = module.eks.cluster_security_group_id
}

output "node_security_group_id" {
  description = "EKS worker node security group ID )"
  value       = module.eks.node_security_group_id
}

output "vpc_id" {
  description = "VPC ID used by EKS"
  value       = data.terraform_remote_state.vpc.outputs.vpc_id
}

output "private_subnets" {
  description = "Private subnet IDs used by EKS nodes"
  value       = data.terraform_remote_state.vpc.outputs.private_subnets
}
