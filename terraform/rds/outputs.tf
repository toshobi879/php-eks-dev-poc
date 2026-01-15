output "db_host" {
  description = "RDS endpoint hostname"
  value       = aws_db_instance.mysql.address
}

output "db_port" {
  description = "RDS port"
  value       = aws_db_instance.mysql.port
}

output "db_name" {
  description = "Database name"
  value       = var.db_name
}

output "db_user" {
  description = "Database username"
  value       = var.db_username
}
