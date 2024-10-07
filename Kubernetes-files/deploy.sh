#!/bin/bash

# Apply the namespace
microk8s kubectl apply -f /home/admin-ccs04/kubernetes/namespace.yaml
sleep 10

# Apply the persistent volume
microk8s kubectl apply -f /home/admin-ccs04/kubernetes/deployment-pv.yaml
sleep 10

# Apply the MySQL deployment
microk8s kubectl apply -f /home/admin-ccs04/kubernetes/deployment-mysql-PV.yaml
sleep 10

# Apply the Laravel deployment
microk8s kubectl apply -f /home/admin-ccs04/kubernetes/deployment-laravel-PV.yaml
sleep 10

# Apply the PHPMyAdmin deployment
microk8s kubectl apply -f /home/admin-ccs04/kubernetes/deployment-phpmyadmin.yaml

echo "----------------------------------"
echo "Deployment completed successfully!"
echo "----------------------------------"