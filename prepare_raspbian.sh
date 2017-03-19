#!/bin/sh
if [ $# -lt 2 ]
  then
  echo "Works only on osx"
  echo "Check for your USB stick, and call the script with the device name and path to image!"
  echo "example: ${0} /dev/diskn path/to/image.img"
  diskutil list
fi

set -xe

if [ $# -eq 2 ]
  then
  diskutil unmountDisk /dev/$1
  sudo dd if=$2 of=/dev/r$1 bs=1m
  touch /Volumes/boot/ssh
  diskutil unmountDisk /dev/$1
fi
set +xe
