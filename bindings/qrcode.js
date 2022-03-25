
const val = (id,fallback=0) => {
    let elem = document.getElementById(id);
    if (elem && elem.value!="") { return elem.value; }
    return fallback;
}

const checked = (id,fallback=false) => {
	let elem = document.getElementById(id);
	if (elem) { return elem.checked; }
	return fallback;
}

const generateNumericCodes = (start_val,end_val,increment,prefix,suffix,pad,target=null,append=false) => {
	let out = "";
	let padding = end_val.toString().length;
	for (let i=start_val; i<=end_val; i+=increment) {
		if (pad) {
			let st = i.toString();
			out += prefix+'0'.repeat(padding - st.length)+st+suffix+'\n';
		} else {
			out += prefix+i+suffix+'\n';
		}
	}
	out = out.slice(0, -1);
	if (target) {
		let elem = document.getElementById(target);
		if (elem) {
			if (append) {
				if (elem.value.length > 0) {
					out = '\n'+out;
				}
				elem.value += out;
			} else {
				elem.value = out;
			}
			return true;
		} else {
			return false;
		}
	} else {
		return out;
	}
}

/* drag'n'drop */
const dragNdrop = (codesElem) => {
	if (codesElem) {
        codesElem.addEventListener('focus',(e)=> {
            if (codesElem.dataset.default == "true") {
				codesElem.dataset.default = "false";
				if (codesElem.value == "http://sirajunnasihin.my.id") {
					codesElem.value = "";
				}
            }
        }),{'once': true};
        const preventDefaults = (e) => {
            e.preventDefault();
            e.stopPropagation();
        }
		const CSVToArray = ( strData, strDelimiter ) => {
			strDelimiter = (strDelimiter || ",");
			var objPattern = new RegExp(("(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +	"(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +	"([^\"\\" + strDelimiter + "\\r\\n]*))"),"gi");
			var arrData = [[]];
			var arrMatches = null;
			while (arrMatches = objPattern.exec( strData )){
				var strMatchedDelimiter = arrMatches[ 1 ];
				if (strMatchedDelimiter.length && strMatchedDelimiter !== strDelimiter){
					arrData.push( [] );
				}
				var strMatchedValue;
				if (arrMatches[ 2 ]){
					strMatchedValue = arrMatches[ 2 ].replace(new RegExp( "\"\"", "g" ),"\"");
				} else {
					strMatchedValue = arrMatches[ 3 ];
				}
				arrData[ arrData.length - 1 ].push( strMatchedValue );
			}
			return( arrData );
		}

		const processFile = (file) => {
			let go = true;
			if (!file.type.startsWith("text")) {
				go = confirm("This file doesn't look like a CSV document. Try to load it anyway?");
			}
			if (go) {
				let reader = new FileReader();
				reader.onload = function (event) {
					try {
						let csv = event.target.result;
						let data = CSVToArray(csv);
						let sep = val('sep',"\n");
						data = data.join(sep);
						codesElem.value = data;
						codesElem.dataset.default = "false";
						updatePreview();
					} catch (e) {
						console.warn(e);
					}
				};
				reader.readAsText(file);
			}
		}

		const handlerFunction = (e) => {
			if (e.type === 'dragenter') {
				codesElem.classList.add("drop");
			} else if (e.type === 'dragleave') {
				codesElem.classList.remove("drop");
			} else if (e.type === 'drop') {
				codesElem.classList.remove("drop");
				let dt = e.dataTransfer;
				let files = dt.files;
				if (files.length > 0) {
					processFile(files[0]);
				}
			}
		}
		
		['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
			codesElem.addEventListener(eventName, preventDefaults, false);
		})
		
		codesElem.addEventListener('dragenter', handlerFunction, false);
		codesElem.addEventListener('dragleave', handlerFunction, false);
		codesElem.addEventListener('drop', handlerFunction, false);
    }
}