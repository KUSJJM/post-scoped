        var fileSection = document.getElementById("file-choice-section");
        var getSelectElement = document.getElementsByClassName("fileChoice")[0];
        var fileSelector = getSelectElement.cloneNode(true);
        
        fileSection.removeChild(fileSection.lastChild);
        fileSection.removeChild(fileSection.lastChild);
        
        document.getElementById("addFileChoice").onclick = function() {
            addFile();
            return false;
        }
        
        document.getElementById("removeFileChoice").onclick = function() {
            removeFile();
            return false;
        }
        
        var i = 1;
        
        function addFile(){
            if (i <= 10){
                i++;
                tempFileSelector = getSelectElement.cloneNode(true);
                fileSection.appendChild(tempFileSelector);
            }
            return false;
        }

        function removeFile() {
            if(i > 0) {
                fileSection.removeChild(fileSection.lastChild);
                i--;
            }
            return false;
        }
        
        //Start of Link Add/Remove script

        var linkSection = document.getElementById("link-choice");
        
        document.getElementById("addLinkChoice").onclick = function() {
            addLink();
            return false;
        }
        
        document.getElementById("removeLinkChoice").onclick = function() {
            removeLink();
            return false;
        }
        
        var j = 0;
                    
        function addLink(){
            if (j < 10){
                j++;
                var linkNumb = document.createElement('p');
                linkNumb.innerHTML = "Link " + j;
                var linkName = document.createElement('input');
                linkName.setAttribute("type", "text");
                linkName.setAttribute("name", "linkName");
                var linkHref = document.createElement('input');
                linkHref.setAttribute("type", "text");
                linkHref.setAttribute("name", "linkName");
                linkSection.appendChild(linkNumb);
                linkSection.appendChild(linkName);
                linkSection.appendChild(linkHref);
            }
            return false;
        }

        function removeLink() {
            if(j > 0) {
                linkSection.removeChild(linkSection.lastChild);
                linkSection.removeChild(linkSection.lastChild);
                linkSection.removeChild(linkSection.lastChild);
                j--;
            }
            return false;
        }