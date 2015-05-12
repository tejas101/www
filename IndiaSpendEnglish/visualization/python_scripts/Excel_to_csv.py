import xlrd
import glob
import csv
excel_files = glob.glob("*.xlsx")
for filename in excel_files:
    wb = xlrd.open_workbook(filename)
    sheet_name = wb.sheet_names()
    sh = wb.sheet_by_name('static data file structure MAIN')
    your_csv_file = open(filename[:-5]+'.csv', 'wb')
    wr = csv.writer(your_csv_file, quoting=csv.QUOTE_ALL)
    for rownum in xrange(sh.nrows):
        wr.writerow(sh.row_values(rownum))
    your_csv_file.close()
    
