#!/bin/bash

symfony console c:c --env=prod
chmod -R 777 var/
chmod -R 777 public/uploads/
chmod -R 777 public/img
chmod -R 777 public/images/
npm run build

