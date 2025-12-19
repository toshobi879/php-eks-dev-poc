output "rds_endpoint" { value = aws_db_instance.mysql.address }
output "rds_port"     { value = aws_db_instance.mysql.port }
output "db_name"      { value = var.db_name }
output "db_username"  { value = var.db_username }
