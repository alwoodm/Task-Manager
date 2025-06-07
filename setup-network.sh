#!/bin/bash

# Find your host network interface (replace with your actual interface)
INTERFACE=$(ip -o -4 route show to default | awk '{print $5}')

# Create the macvlan network
docker network create -d macvlan \
  --subnet=192.168.0.0/24 \
  --gateway=192.168.0.1 \
  -o parent=$INTERFACE \
  my_macvlan

echo "Network my_macvlan created successfully"
echo "Run 'docker-compose up -d' to start the services"
