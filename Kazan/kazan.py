
import argparse
import sys

USER = "bosch"
PASSWORD = "bosch60"

parser = argparse.ArgumentParser()
parser.add_argument("--user", default="")
parser.add_argument("--password", default="")
parser.add_argument("--switch", choices=["on", "off"], required=True)
args = parser.parse_args()

if args.user != USER or args.password != PASSWORD:
    
    print("Nem megfelelo felhasznalonev / jelszo")
    sys.exit(1)

if args.switch == "on":
    print("Working")
else:
    print("System down")