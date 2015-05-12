# -*- coding: utf-8 -*-
"""
Created on Tue Jul 16 11:07:51 2013

@author: Muthuvel
"""
import glob
import os
import csv
excel_files = glob.glob("*.csv")
output_file = './output/Static data transformed.csv'
output_dir = os.path.dirname(output_file)
if not os.path.exists(output_dir):
    os.mkdir(output_dir)
    
fout=open(output_file,"a")
count = -1
for filename in excel_files:    
    print filename
    count = count + 1
    if count == 0:
        # first file:
        for line in open(filename):
            fout.write(line)
    else:
        f = open(filename)
        f.next() # skip the header
        for line in f:
            fout.write(line)
        f.close() # not really needed
fout.close()