#!/bin/bash

# Define the base directory
BASE_DIR="src"

# Define the directory structure
DIRS=(
  "$BASE_DIR/Application/Command"
  "$BASE_DIR/Application/Query"
  "$BASE_DIR/Application/Service"
  "$BASE_DIR/Application/Dto"
  "$BASE_DIR/Application/Exception"
  "$BASE_DIR/Domain/Model/Client"
  "$BASE_DIR/Domain/Model/Order"
  "$BASE_DIR/Domain/Model/Product"
  "$BASE_DIR/Domain/Repository"
  "$BASE_DIR/Domain/Service"
  "$BASE_DIR/Domain/Exception"
  "$BASE_DIR/Infrastructure/Persistence/Doctrine/Repository"
  "$BASE_DIR/Infrastructure/Http/Controller"
  "$BASE_DIR/Infrastructure/Http/Request"
  "$BASE_DIR/Infrastructure/Http/Response"
  "$BASE_DIR/Infrastructure/Messaging"
  "$BASE_DIR/Presentation/Web/Controller"
  "$BASE_DIR/Presentation/Web/ViewModel"
)

# Create the directories
for DIR in "${DIRS[@]}"; do
  mkdir -p "$DIR"
done

echo "Directories created successfully."
