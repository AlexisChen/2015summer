

var stopWords = ["a", "about", "above", "across", "after", "again", "against", "all", "almost", "alone", "along", "already", "also", "although", "always", "among", "an", "and", "another", "any", "anybody", "anyone", "anything", "anywhere", "are", "area", "areas", "around", "as", "ask", "asked", "asking", "asks", "at", "away", "b", "back", "backed", "backing", "backs", "be", "became", "because", "become", "becomes", "been", "before", "began", "behind", "being", "beings", "best", "better", "between", "big", "both", "but", "by", "c", "came", "can", "cannot", "case", "cases", "certain", "certainly", "clear", "clearly", "come", "could", "d", "did", "differ", "different", "differently", "do", "does", "done", "down", "down", "downed", "downing", "downs", "during", "e", "each", "early", "either", "end", "ended", "ending", "ends", "enough", "even", "evenly", "ever", "every", "everybody", "everyone", "everything", "everywhere", "f", "face", "faces", "fact", "facts", "far", "felt", "few", "find", "finds", "first", "for", "four", "from", "full", "fully", "further", "furthered", "furthering", "furthers", "g", "gave", "general", "generally", "get", "gets", "give", "given", "gives", "go", "going", "good", "goods", "got", "great", "greater", "greatest", "group", "grouped", "grouping", "groups", "h", "had", "has", "have", "having", "he", "her", "here", "herself", "high", "high", "high", "higher", "highest", "him", "himself", "his", "how", "however", "i", "if", "important", "in", "interest", "interested", "interesting", "interests", "into", "is", "it", "its", "itself", "j", "just", "k", "keep", "keeps", "kind", "knew", "know", "known", "knows", "l", "large", "largely", "last", "later", "latest", "least", "less", "let", "lets", "like", "likely", "long", "longer", "longest", "m", "made", "make", "making", "man", "many", "may", "me", "member", "members", "men", "might", "more", "most", "mostly", "mr", "mrs", "much", "must", "my", "myself", "n", "necessary", "need", "needed", "needing", "needs", "never", "new", "new", "newer", "newest", "next", "no", "nobody", "non", "noone", "not", "nothing", "now", "nowhere", "number", "numbers", "o", "of", "off", "often", "old", "older", "oldest", "on", "once", "one", "only", "open", "opened", "opening", "opens", "or", "order", "ordered", "ordering", "orders", "other", "others", "our", "out", "over", "p", "part", "parted", "parting", "parts", "per", "perhaps", "place", "places", "point", "pointed", "pointing", "points", "possible", "present", "presented", "presenting", "presents", "problem", "problems", "put", "puts", "q", "quite", "r", "rather", "really", "right", "right", "room", "rooms", "s", "said", "same", "saw", "say", "says", "second", "seconds", "see", "seem", "seemed", "seeming", "seems", "sees", "several", "shall", "she", "should", "show", "showed", "showing", "shows", "side", "sides", "since", "small", "smaller", "smallest", "so", "some", "somebody", "someone", "something", "somewhere", "state", "states", "still", "still", "such", "sure", "t", "take", "taken", "than", "that", "the", "their", "them", "then", "there", "therefore", "these", "they", "thing", "things", "think", "thinks", "this", "those", "though", "thought", "thoughts", "three", "through", "thus", "to", "today", "together", "too", "took", "toward", "turn", "turned", "turning", "turns", "two", "u", "under", "until", "up", "upon", "us", "use", "used", "uses", "v", "very", "w", "want", "wanted", "wanting", "wants", "was", "way", "ways", "we", "well", "wells", "went", "were", "what", "when", "where", "whether", "which", "while", "who", "whole", "whose", "why", "will", "with", "within", "without", "work", "worked", "working", "works", "would", "x", "y", "year", "years", "yet", "you", "young", "younger", "youngest", "your", "yours", "z","0","1","2","3","4","5","6","7","8","9","48","ac"]

var set = new Set(stopWords);



/*
 this function gets a string array, calculate each string frequency,
 and sorted in descending order based on the highest frequency
 */
function search(in_data) {
    var data = in_data;
    var temp = data;
    var words = new Array();
    words = temp.split(" ");
    words = tolower(words);

    var uniqueWords = new Array();
    var count = new Array();
    var wordArray = new Array();

    // calculate each word frequency
    // console.log(words.length);
    for (var i = 0; i < words.length; i++) {
        var freq = 0;
        for (j = 0; j < uniqueWords.length; j++) {
            if (words[i] == uniqueWords[j]) {
                count[j] = count[j] + 1;
                freq = 1;
            }
        }
        if (freq == 0) { 
            count[i] = 1;
            uniqueWords[i] = words[i];
        }
    }

    for (var i=0; i<uniqueWords.length; i++) {
        if (count[i] > 0) {
            wordArray.push({word: uniqueWords[i], freq: count[i]});
        }
    }

    // sorting
    wordArray.sort(function(first,second) {
        return second.freq - first.freq;
    });

    // for (var i= wordArray.length - 1; i >=0; i--) {
    //     if (set.has(wordArray[i].word)) {
    //         // console.log("deleting: " + wordArray[i].word);
    //         wordArray.splice(i,1);
    //     }
    // }
        //console.log("57");
        for (var i= wordArray.length - 1; i >=0; i--) {
                if (set.has(wordArray[i].word)) {
                    //console.log("deleting: " + wordArray[i].word);
                    wordArray.splice(i,1);
                }
            }    
    
    //this process the cloud:
    var to_process = wordArray.slice(0, 250);
    // console.log("this is the arraysize "+ to_process.length);
    generateCloud(to_process);
}

// convert words found to lower case
function tolower(strArray) {
    var lowerTemp = new Array();
    for (var i=0; i<strArray.length; i++) {
        lowerTemp[i] = strArray[i].toLowerCase();
    }

    return lowerTemp;
}

