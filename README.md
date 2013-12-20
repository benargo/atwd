# Advanced Topics in Web Development
UFCEWT-20-3

## Problems encountered
1. Excel file bloat and nested figures meant I had to cut out a lot of excess rows and hard code the headers
2. XML has attributes and children, JSON has just children

## Source Code

All code is hosted at [Github](https://github.com/benargo/atwd).

1. Data Conversion and Schema
	1. [Input CSV File](https://github.com/benargo/atwd/blob/master/data/recorded_crime.csv) ([Download](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/recorded_crime.csv))
	2. [Upload Form](https://github.com/benargo/atwd/blob/master/data/upload.get.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/upload.get.phps))
	3. [POST CSV Processor](https://github.com/benargo/atwd/blob/master/data/upload.post.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/upload.post.phps))
	3. [Generated XML File](https://github.com/benargo/atwd/blob/master/data/recorded_crime.xml) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/recorded_crime.xml))
	4. [XSD Schema](https://github.com/benargo/atwd/blob/master/data/recorded_crime.xsd) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/recorded_crime.xsd))
	5. [Custom Areas template](https://github.com/benargo/atwd/blob/master/data/custom_areas.template.xml) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/custom_areas.template.xml))
2. RESTful Service
	1. [Autoloader](https://github.com/benargo/atwd/blob/master/api/autoload.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/autoload.phps))
	2. Classes
		1. [Area](https://github.com/benargo/atwd/blob/master/api/classes/area.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/classes/area.phps))
		2. [Error](https://github.com/benargo/atwd/blob/master/api/classes/error.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/classes/error.phps))	
		3. [Region](https://github.com/benargo/atwd/blob/master/api/classes/region.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/classes/region.phps))
		4. [Response](https://github.com/benargo/atwd/blob/1cc57d96595644c8633cdc87f2ad3b7f7d5a1570/api/classes/response.php) (File removed from final version)
		5. [SimpleXMLIterator](https://github.com/benargo/atwd/blob/master/api/classes/SimpleXMLIterator.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/classes/SimpleXMLIterator.phps))
		6. [URI](https://github.com/benargo/atwd/blob/master/api/classes/uri.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/classes/uri.phps))
	3. Functions
		1. [dump()](https://github.com/benargo/atwd/blob/master/api/functions/dump.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/functions/dump.phps))
		2. [fatal_error()](https://github.com/benargo/atwd/blob/master/api/functions/fatal_error.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/functions/fatal_error.phps))
		3. [get_file()](https://github.com/benargo/atwd/blob/25b03e2e10ed4d59ebe7022c20335c10adc4dc4c/api/functions/get_file.php) (File removed from final version)
	4. RESTful Pages
		1. [GET all regions](https://github.com/benargo/atwd/blob/master/api/getAll.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/getAll.phps))
		2. [GET specific region](https://github.com/benargo/atwd/blob/master/api/get.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/get.phps))
		3. [PUT (update) total](https://github.com/benargo/atwd/blob/master/api/put.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/put.phps))
		4. [POST (create) new area](https://github.com/benargo/atwd/blob/master/api/post.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/post.phps))
		5. [DELETE custom area](https://github.com/benargo/atwd/blob/master/api/delete.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/delete.phps))
		6. [Catch for unmapped requests](https://github.com/benargo/atwd/blob/master/api/catch_all.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/api/catch_all.phps))
3. Client Side Processing & Visualization

![UWE logo](http://www.cems.uwe.ac.uk/~b2-argo/atwd/media/images/uwe.75px.png)