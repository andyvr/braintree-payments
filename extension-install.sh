#!/bin/sh

cd ./vendor/andyvr/braintree-payments
rsync -Rarz admin ../../../
rsync -Rarz catalog ../../../
rm -Rf vendor