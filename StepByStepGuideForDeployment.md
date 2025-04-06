# Step by Step for AWS EC2 Deployment

- [Step by Step for AWS EC2 Deployment](#step-by-step-for-aws-ec2-deployment)
  - [Set up the Security Groups](#set-up-the-security-groups)
    - [ELBSecurityGroup](#elbsecuritygroup)
    - [FrontEndSecurityGroup](#frontendsecuritygroup)
    - [BackEndSecurityGroup](#backendsecuritygroup)
    - [RDSSecurityGroup](#rdssecuritygroup)
  - [How to SSH into the EC2 instances](#how-to-ssh-into-the-ec2-instances)
  - [Set up EC2 Target Group](#set-up-ec2-target-group)
  - [Set up the EC2 Elastic Load Balancer (ELB)](#set-up-the-ec2-elastic-load-balancer-elb)
  - [Set up the Aurora \& RDS Database](#set-up-the-aurora--rds-database)
  - [Set up the EC2 PHP Server](#set-up-the-ec2-php-server)
  - [Set up the ReactJS Server](#set-up-the-reactjs-server)
    - [Settings](#settings)
    - [Connect to the instance](#connect-to-the-instance)
  - [Creating ReactJS Image](#creating-reactjs-image)
  - [Creating the EC2 Auto Scaling](#creating-the-ec2-auto-scaling)
  - [Finished!](#finished)
  - [Other Commands / Troubleshooting](#other-commands--troubleshooting)
  - [Testing from the EC2 Instance Console (optional)](#testing-from-the-ec2-instance-console-optional)

## Set up the Security Groups

```sh
# Note your IP Address.
curl ifconfig.me
```

\* _MyIPAddress_ is set only during when we to configure the servers.
Afterwards, the security groups are modified so that no SSH is allowed.

You can modify _MyIPAddress_ as needed in the security groups.

### ELBSecurityGroup

Description:
Allows HTTP/HTTPS to ELB from anywhere. ELB sends traffic anywhere.

**Inbound Traffic**

- HTTP/HTTPS, **Anywhere-IPv4**, 0.0.0.0/0

**Outbound Traffic**

- All traffic, **Anywhere-IPv4**, 0.0.0.0/0

### FrontEndSecurityGroup

Description:
Allows HTTP/HTTPS to Frontend from ELB and Backend. SSH from my IP. Frontend sends traffic anywhere.

**Inbound Traffic**

- SSH, _MyIPAddress_
- HTTP/HTTPS, **ELBSecurityGroup**
- HTTP/HTTPS, **BackEndSecurityGroup**

**Outbound Traffic**

- HTTP/HTTPS, Anywhere-IPv4, 0.0.0.0/0

### BackEndSecurityGroup

Description:
Allows HTTP/HTTPS/MySQL from Backend and RDS. SSH from my IP. Backend sends HTTPHTTP/MySQL back.

\*\* Only add this rule if you want to be able to see the backend PHPs.
Only deployment remove these rules.

**Inbound Traffic**

- SSH, _MyIPAddress_
- HTTP/HTTPS, **ELBSecurityGroup**
- HTTP/HTTPS, **FrontEndSecurityGroup**
- HTTP/HTTPS/MySQL, **RDSSecurityGroup**

- \*\* HTTP/HTTPS, **Anywhere-IPv4**, 0.0.0.0/0

**Outbound Traffic**

- HTTP/HTTPS, **ELBSecurityGroup**
- HTTP/HTTPS, **FrontEndSecurityGroup**
- HTTP/HTTPS/MySQL, **RDSSecurityGroup**

- \*\* HTTP/HTTPS, **Anywhere-IPv4**, 0.0.0.0/0

### RDSSecurityGroup

Description:
Allows HTTP/HTTPS/MySQL from Backend. RDS sends HTTP/HTTPS/MySQL back.

**Inbound Traffic**

- HTTP/HTTPS/MySQL, **BackEndSecurityGroup**

**Outbound Traffic**

- HTTP/HTTPS/MySQL, **BackEndSecurityGroup**

## How to SSH into the EC2 instances

Open the WSL command line the location where SofaSoGoodRSAKey.

Generation of the key can be found [here](#set-up-the-ec2-php-server).

```sh
# ------- protect your key
chmod 400 SofaSoGoodRSAKey.pem
# check key permissions
ls -l SofaSoGoodRSAKey.pem
# should be -r-------- 1 ... SofaSoGoodRSAKey.pem

# ------- ssh into the EC2 instance
ssh -i "SofaSoGoodRSAKey.pem" ubuntu@ec2-xx-xx-xx-xx.compute-1.amazonaws.com
```

## Set up EC2 Target Group

**Step 1**

```
Target Type       : Instances
Target Group Name : SofaSoGoodJSXTarget
Protocol          : HTTP
Protcol version   : HTTP1
```

**Step 2**

`Create target group`

## Set up the EC2 Elastic Load Balancer (ELB)

**Step 1**

Choose Application Load Balancer.

**Step 2**

```
Load balancer name    : SofaSoGoodJSXELB
Scheme                : Internet-facing
Availability Zone     : us-east-1a
                        us-east-1b
Security Group        : ELBSecurityGroup
Listeners and routing : HTTP 80, SofaSoGoodJSXTarget
```

**Step 3**

`Create load balancer`

**Step 4**

Select SofaSoGoodJSXELB and record the DNS name.

## Set up the Aurora & RDS Database

```
Create                      : standard create
Engine                      : MySQL
Templates                   : Free tier
DB Instance Identifier      : sofasogoodDatabase
Username                    : [DBUsername]
Master Password             : [DBPassword]
Instance config             : db.t3.micro
Connectivity                : do not connect
VPC Security Firewall       : choose existing -> RDSSecurityGroup
Inital DB Name (Add Config) : sofasogoodDB
```

Remember the following data:

```
Connectivity & security -> endpoint [DBEndpoint]
Connectivity & security -> port [DBPort]
```

## Set up the EC2 PHP Server

```
Name              : SofaSoGoodPHP
AMI               : ubuntu
Instance type     : t2.micro
Key pair          : create new key pair if you do not already have ->
                    name   : SofaSoGoodRSAKey
                    type   : RSA
                    format : .pem
                    :: download into a secure location
Network, Firewall : BackEndSecurityGroup
Storage           : 8, gp3
```

Do not lose your SofaSoGoodRSAKey, or else you have to regenerate it (beyond the scope of these instructions).

Recommended location to download it is in `/home/user/.ssh`.

SSH into the instance and run the following commands.

```sh
# ------- fetch an IMDSv2 token
TOKEN=$(curl -sX PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600")

# ------- Retrieve instance metadata
INSTANCE_ID=$(curl -sH "X-aws-ec2-metadata-token: $TOKEN" "http://169.254.169.254/latest/meta-data/instance-id")
PRIVATE_IP=$(curl -sH "X-aws-ec2-metadata-token: $TOKEN" "http://169.254.169.254/latest/meta-data/local-ipv4")
PUBLIC_IP=$(curl -sH "X-aws-ec2-metadata-token: $TOKEN" "http://169.254.169.254/latest/meta-data/public-ipv4")
INSTANCE_TYPE=$(curl -sH "X-aws-ec2-metadata-token: $TOKEN" "http://169.254.169.254/latest/meta-data/instance-type")
AVAILABILITY_ZONE=$(curl -sH "X-aws-ec2-metadata-token: $TOKEN" "http://169.254.169.254/latest/meta-data/placement/availability-zone")

# ------- check we have data
echo $INSTANCE_ID $PRIVATE_IP $PUBLIC_IP $INSTANCE_TYPE $AVAILABILITY_ZONE

# ------- export environment variables for PHP
# gave up on environment, now regenerating from scratch
# echo "INSTANCE_ID=$INSTANCE_ID" | sudo tee -a /etc/environment
# echo "PRIVATE_IP=http://$PRIVATE_IP" | sudo tee -a /etc/environment
# echo "PUBLIC_IP=http://$PUBLIC_IP" | sudo tee -a /etc/environment
# echo "INSTANCE_TYPE=$INSTANCE_TYPE" | sudo tee -a /etc/environment
# echo "AVAILABILITY_ZONE=$AVAILABILITY_ZONE" | sudo tee -a /etc/environment

# ------- reboot the system so it will reread the environment variables
#sudo reboot

# Reconnect to the EC2 instance.

# ------- check the environment variables
# printenv INSTANCE_ID PRIVATE_IP PUBLIC_IP INSTANCE_TYPE AVAILABILITY_ZONE

# ------- configure setup
sudo apt update -y
sudo apt upgrade -y
sudo apt install npm nginx git php php-fpm php-mysql -y

# ------- clone the project
git clone --depth 1 https://github.com/EzraEmma/csd3156-ezra.git
# we do not need the ReactJS side
sudo rm -r csd3156-ezra/my-app
cd csd3156-ezra/DataBaseStuff/

# ------- autogenerate the metadata include file
# ------- replace the EC2MetadataInclude.php with the correct values
cat <<EOF | tee EC2MetadataInclude.php
<?php
/**
 * Autogenerated from EC2 instance.
 */
define('EC2_INSTANCE_ID', '${INSTANCE_ID}');
define('EC2_PRIVATE_IP', '${PRIVATE_IP}');
define('EC2_PUBLIC_IP', '${PUBLIC_IP}');
define('EC2_INSTANCE_TYPE', '${INSTANCE_TYPE}');
define('EC2_AVAILABILITY_ZONE', '${AVAILABILITY_ZONE}');
?>
EOF

# ------- edit dbinfo.inc
sudo rm dbinfo.inc # if sure you want to replace
sudo nano dbinfo.inc
```

Replace:

```php
<?php

define('DB_SERVER', 'DBEndpoint');
define('DB_USERNAME', 'DBUsername');
define('DB_PASSWORD', 'DBPassword');
define('DB_DATABASE', 'sofasogoodDB');

?>
```

Continue.

```sh
# ------- start configure nginx
# stopping apache2 because sometimes it's already running
sudo systemctl stop apache2
sudo systemctl disable apache2
sudo systemctl start nginx
sudo systemctl start php8.3-fpm
sudo # remove the default config
sudo rm /etc/nginx/sites-enabled/default
# move over new config
sudo cp -r php-api /etc/nginx/sites-available/php-api

# ------- enable the site
sudo ln -s /etc/nginx/sites-available/php-api /etc/nginx/sites-enabled/
sudo nginx -t # verifies nginx
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx

# -------  deploy the php as it is
sudo mkdir -p /var/www/php-api
sudo cp -r ../DataBaseStuff/* /var/www/php-api

# -------  set correct permissions
sudo chown -R www-data:www-data /var/www/php-api
sudo chmod -R 755 /var/www/php-api

# -------  restart site
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
```

## Set up the ReactJS Server

### Settings

```
Name          : SofaSoGoodJSX
AMI           : ubuntu
Instance type : t2.micro
Key pair      : SofaSoGoodRSAKey
Network       : FrontEndSecurityGroup
Storage       : 8, gp3
```

### Connect to the instance

Please write down the PHP's public IP address here. Make sure there is no `/` at the end.

```sh
PHP_URL=http://xx.xx.xx.xx
echo $PHP_URL
```

Continue.

```sh
# ------- configure setup
sudo apt update -y
sudo apt upgrade -y
sudo apt install npm nginx git php php-fpm php-mysql -y

# ------- clone the project
git clone --depth 1 https://github.com/EzraEmma/csd3156-ezra.git
# we do not need the database side
sudo rm -r csd3156-ezra/DataBaseStuff
cd csd3156-ezra/my-app/

# ------- replace the AppInclude.jsx with the correct values
cat <<EOF | tee src/AppInclude.jsx
/**
 * Autogenerated by the EC2 instance.
 */
export const PHP_URL = "$PHP_URL";
EOF

# ------- make the builds for vite and reactjs
npm install
npm audit fix
npm run build

# ------- start configure nginx
# stopping apache2 because sometimes it's already running
sudo systemctl stop apache2
sudo systemctl disable apache2
sudo systemctl start nginx
sudo systemctl start php8.3-fpm
sudo # remove the default config
sudo rm /etc/nginx/sites-enabled/default
# move over new config
sudo cp -r vite-app /etc/nginx/sites-available/vite-app

# ------- enable the site
sudo ln -s /etc/nginx/sites-available/vite-app /etc/nginx/sites-enabled/
sudo nginx -t # verifies nginx
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx

# -------  deploy build files for vite reactjs
sudo mkdir -p /var/www/vite-app/dist
sudo cp -r dist/* /var/www/vite-app/dist

# -------  set correct permissions
sudo chown -R www-data:www-data /var/www/vite-app
sudo chmod -R 755 /var/www/vite-app

# -------  restart site
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
```

## Creating ReactJS Image

1. Select SofaSoGoodJSX.
2. Click `Action`.
3. Click `Create Image`.
4. Name the image `SofaSoGoodJSXImage`.
5. Describe image `An image of the SofaSoGood ReactJS frontend.`.
6. Click `Create Image`.

## Creating the EC2 Auto Scaling

**Step 1**

```
Choose launch template
Name                               : SofaSoGoodJSXAutoScaler
Launch Template                    : Create a launch template ->
                                     Template Name                      : SofaSoGoodJSXLaunchTemplate
                                     Version Descr.                     : A production SofaSoGoodJSX web server
                                     Launch template, My AMIs           : SofaSoGoodJSXImage
                                     Instance type                      : t2.micro
                                     Key pair (login)                   : SofaSoGoodRSAKey
                                     Networking Setting, Security Group : FrontEndSecurityGroup
                                     :: Create launch template
                                     :: Choose SofaSoGoodJSXLaunchTemplate

Choose instance launch options
Availability zones : us-east-1a
                     us-east-1b

Integrate with other services
Loading balancing                   : Attach to existing load balancer
Attach to an existing load balancer : Choose from your load balancer groups
                                      Select target groups: SofaSoGoodJSXTarget

Configure group size and scaling
Group size, Desired capacity                                : 2
Scaling, min desired capacity                               : 2
         max desired capacity                               : 6
Automatic scaling, target tracking policy                   : SofaSoGoodJSXTargetScalingPolicy
                   metric type                              : Average CPU Utilization
                   target value                             : 60
Additional settings, enable group metrics within CloudWatch : Enable

Add notifications
SNS Topic : Redshift SNS
```

**Step 2**

`Create auto scaling group`

## Finished!

Now you should be able to access the webpages from the ELB DNS (step 4 of
the [ELB-Creation](#set-up-the-ec2-elastic-load-balancer-elb)).


## Other Commands / Troubleshooting

> **Troubleshooting PHP not starting 1: can't find your php**
>
> ```sh
> sudo systemctl list-units --type=service | grep php
> ```
>
> Then use that version indicated.

> **Troubleshooting PHP not starting 2: because Apache is Running**
>
> Detection:
>
> ```sh
> sudo lsof -i :80
> ```
>
> ```sh
> sudo systemctl stop apache2
> sudo systemctl disable apache2
> sudo systemctl restart nginx
> sudo systemctl restart php8.3-fpm
> ```
>
> Then use that version indicated.

```sh
# if necessary for update/push
git fetch
git pull
```

```sh
# stopping/restarting nginx
sudo systemctl stop nginx
sudo systemctl restart nginx
```

```sh
# getting errors from nginx
sudo tail -f /var/log/nginx/error.log
```

## Testing from the EC2 Instance Console (optional)

This should be done on the PHP server as the RDS database access is restricted
to only the BackendSecurityGroup.

```sh
sudo wget https://dev.mysql.com/get/mysql-apt-config_0.8.29-1_all.deb
sudo dpkg -i mysql-apt-config_0.8.29-1_all.deb
```

Choose `Ubuntu Jammy`.
Choose `Ok`.

```sh
sudo apt update
sudo apt upgrade
sudo apt install mysql-client -y
sudo mysql -h DBEndpoint -u DBUsername -p
```

```console
mysql>Show databases;
```

This should show the databases.