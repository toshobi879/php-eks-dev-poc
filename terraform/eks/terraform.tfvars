aws_region = "us-west-2"

project_name = "caltrans-eks-php"

eks_cluster_version = "1.33"

node_instance_type = "t3.medium"

desired_capacity = 2

github_actions_role_arn = "arn:aws:iam::020408743573:role/php-eks-dev-poc-role"

sso_admin_role_arn = "arn:aws:iam::020408743573:role/aws-reserved/sso.amazonaws.com/us-west-2/AWSReservedSSO_AWSAdministratorAccess_3fed78f1fb50999a"
