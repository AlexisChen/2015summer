function createFake(){
	var fake_objects = new Array();//list of fake objects
	for(var i = 0; i < 10; i ++){

		var authorlist =  ["author"+i+"_1", "author"+i+"_2", "author"+i+"_3"];

		var each_fake = {paperId : i, freq : i, 
			paperTitle : "title"+i, author : authorlist,
			conference:"conference"+i, linkToDownload:"link to download"+i,
			linkToBib:"link to bib"+i};
		fake_objects.push(each_fake);
	}
	return fake_objects;
}