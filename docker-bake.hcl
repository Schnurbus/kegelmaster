group "default" {
  targets = ["kegelmaster-frankenphp"]
}

target "kegelmaster" {
  context = "."
  dockerfile = "RoadRunner.Dockerfile"
  tags = ["astranger/kegelmaster:dev"]
}

target "kegelmaster-frankenphp" {
  context = "."
  dockerfile = "FrankenPHP.Dockerfile"
  tags = ["astranger/kegelmaster:dev"]
}
