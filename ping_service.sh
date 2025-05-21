#!/bin/bash
while true; do
  echo "Pinging the app to keep it alive..."
  curl https://website-builder-z7ch.onrender.com > /dev/null 2>&1
  sleep 300  # Sleep for 5 minutes before pinging again
done
