group "default" {
  targets = ["kegelmaster-frankenphp"]
}

target "kegelmaster-frankenphp" {
  context = "."
  dockerfile = "deployment/Dockerfile"
  tags = ["astranger/kegelmaster:dev"]
}
