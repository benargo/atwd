# Advanced Topics in Web Development

[[Assignment Specification](http://www.cems.uwe.ac.uk/~p-chatterjee/modules/atwd/assignment/ATWD_Assignment_2013-14.html)]

## Accessing the Assignment

1. **Data Conversion and Schema:** [http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/upload](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/upload)
2. **REST Service:** Published at specified URLs
3. **Client Side Processing and Visualization:** [http://www.cems.uwe.ac.uk/~b2-argo/atwd/client](http://www.cems.uwe.ac.uk/~b2-argo/atwd/client/index.html)
4. **Documentation:** [http://www.cems.uwe.ac.uk/~b2-argo/atwd/crimes/doc/index.html](http://www.cems.uwe.ac.uk/~b2-argo/atwd/crimes/doc/index.html)

## Learning Outcomes & Problems Encountered

Instead of using a heavy MySQL database for storing custom data produced by the API, I opted instead for storing the data in XML files on the file system. These files can still be queried based on subdirectories (representing tables and then sorting by keys) and file names (representing IDs). For example, the custom area of "Wessex" in the "South West" region will be stored in `/data/custom/areas/south_west/wessex.xml`. I felt this the most appropriate solution for handling small amounts of custom data given the available technologies on UWE's web server. This method of storing data in the file system and having the API parse the XML was suitably fast for this application. If server side caching was to be taken further, I would investigate using PHP's [Serialise](http://www.php.net/manual/en/function.serialize.php) function to store the data as a PHP object. 

The first problem encountered was with converting the supplied Excel spreadsheet to CSV. The Excel sheet contains a lot of excess rows, which all had to be stripped out. The data also contained a large number of totals, which also had to be removed. The conversion had to be hard coded for this specific format, as a traditional header based solution and then looping through each of the columns would not have worked. In all, the supplied data was a mess and it took a great deal more than anticipated to convert it to XML. The downside of this meant that if the CSV is changed and formatted differently in a previous update, the application would not be able to handle the conversion. The overall feeling of this process was that I've managed to make a meal out of garbage, but if the garbage was slightly different a meal would be impossible.

The second problem I encountered was differences in formatting data. XML has both attributes and children, which whilst makes formatting items more complex, has the advantage of being able to create more complex structures. JSON only supports child elements, so items that would be attributes in the XML have no choice to be children in the JSON. This presented a tricky problem when I had to create two child nodes with the same name. 

Finally, an issue with client side caching arose, as per the assignment brief the timestamp returned by the API is the current UNIX timestamp. This means that the data effectively updates itself every second as the timestamp changes, even though the data itself can remain the same. This meant that the AJAX's request to the API would always return as HTTP 200 (OK), rather than 304 (Not Modified), making local caching impossible. That said, I was able to store the data in local storage and, should the AJAX request return a 304 then it will use the locally stored data.

To conclude, this assignment was fairly straight forward for me. That being said, this is the first time I've made an API, and the first major time I've produced XML and JSON as the result being echoed by PHP. It's a shame that the brief was asking for PUT/POST/DELETE requests which were really GET requests performing CRUD (Create, Read, Update & Delete) operations, I would have preferred to utilise the HTTP methods correctly. Nonetheless it works.

## Source Code

All code is hosted at [Github](https://github.com/benargo/atwd) with backups on UWE's web server.

### 1. Data Conversion and Schema
1. [Input CSV File](https://github.com/benargo/atwd/blob/master/data/recorded_crime.csv) ([Download](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/recorded_crime.csv))
2. [Upload Form](https://github.com/benargo/atwd/blob/master/data/upload.get.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/upload.get.phps))
3. [POST CSV Processor](https://github.com/benargo/atwd/blob/master/data/upload.post.php) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/upload.post.phps))
3. [Generated XML File](https://github.com/benargo/atwd/blob/master/data/recorded_crime.xml) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/recorded_crime.xml))
4. [XSD Schema](https://github.com/benargo/atwd/blob/master/data/recorded_crime.xsd) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/recorded_crime.xsd))
5. [Custom Areas template](https://github.com/benargo/atwd/blob/master/data/custom_areas.template.xml) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/data/custom_areas.template.xml))

### 2. RESTful Service
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

### 3. Client Side Processing & Visualization
1. [Index page](https://github.com/benargo/atwd/blob/master/client/index.html) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/client/))
2. [Get a specific region](https://github.com/benargo/atwd/blob/master/client/get-region.html) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/client/get-region.html))

### 4. CSS, JavaScript and Images
1. CSS
	1. [base.css](https://github.com/benargo/atwd/blob/master/media/css/base.css) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/media/css/base.css))
	2. [custom.scss](https://github.com/benargo/atwd/blob/master/media/css/custom.scss) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/media/css/custom.scss))
	3. [layout.css](https://github.com/benargo/atwd/blob/master/media/css/layout.css) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/media/css/layout.css))
	4. [skeleton.css](https://github.com/benargo/atwd/blob/master/media/css/skeleton.css) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/media/css/skeleton.css))
2. JavaScript
	1. [Data Upload](https://github.com/benargo/atwd/blob/master/media/js/data-upload.js) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/media/js/data-upload.js))
	2. [Chart.js Library](https://github.com/benargo/atwd/blob/master/media/js/chart.js) ([Backup](www.cems.uwe.ac.uk/~b2-argo/atwd/media/js/chart.js))
	3. [Client Processing](https://github.com/benargo/atwd/blob/master/media/js/client.js) ([Backup](http://www.cems.uwe.ac.uk/~b2-argo/atwd/media/js/client.js))
3. Images
	1. [Home Office logo](http://www.cems.uwe.ac.uk/~b2-argo/atwd/media/images/home_office.75px.png) (&copy; 2013 Crown Copyright)
	2. [UWE logo](http://www.cems.uwe.ac.uk/~b2-argo/atwd/media/images/uwe.75px.png) (&copy; 2013 University of the West of England, Bristol)
	
### 5. Other Files
1. [.htaccess](https://github.com/benargo/atwd/blob/master/.htaccess)

![UWE logo](http://www.cems.uwe.ac.uk/~b2-argo/atwd/media/images/uwe.75px.png)