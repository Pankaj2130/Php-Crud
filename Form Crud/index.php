<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="./style.css">

  <?php 
   include 'post.php';
  ?>
</head>
<body>
  <h1>Add Customer name</h1>
  <div class="container">
    <div class="mainContainer">
      <form action="hello.php" method="post" id="form">
        <label for="name" id="txt">Enter Your Name</label>
        <br/>
        <p id="message"></p>
        <input type="text" class="input" id="InputVal" placeholder="Enter name...">
        <br /><br />
        <input class="btn" id="btn1" onclick="Save()" type="button" value="Add">
      </form>
      <div class="table">
        <table id="Table">
        </table>
        <div id="syncBtn" class="syncbtn">
          <input style="font-size:21px; margin-top:20px; font-weight:700s" id="syncButton" type="button" onclick="Update()" value="Sync Records">
        </div>
      </div>
    </div>
  </div>

  <script>
      var Array = [];
      var jsonData = <?php echo $jsonData; ?>;
      var table = document.getElementById("Table");
  for (var i = 0; i < jsonData.length; i++) {
    var newTbody = document.createElement('tbody');
    newTbody.className = 'tableData';
    table.appendChild(newTbody);
    var Row = document.createElement('tr');
    var Cell1 = document.createElement('td');
    var Cell2 = document.createElement('td');
    var Cell3 = document.createElement('td');
    var Button = document.createElement('button');
    Button.className = 'edit';
    Button.innerHTML = 'Edit';
    var DeleteButton = document.createElement('button');
    DeleteButton.className = 'delBtn';
    DeleteButton.innerHTML = 'Delete';
    DeleteButton.addEventListener('click', deleteValue(i));
    var val = Button.addEventListener('click', editValue1(i));
    Cell1.innerHTML = i + 1;
    Cell2.innerHTML = jsonData[i].value;
    Cell3.appendChild(Button);
    Cell3.appendChild(DeleteButton);
    Row.appendChild(Cell1);
    Row.appendChild(Cell2);
    Row.appendChild(Cell3);
    newTbody.appendChild(Row);
  }

  //DATA FROM DATABASE;
  var DataArray = jsonData;

  var id;
  function deleteValue(index) {
    
    return function () {
     id = jsonData[index].id;
     if(!confirm("Are you sure you want to delete ?")) return;

     var tbl = this.parentNode.parentNode.parentNode;
     var row = this.parentNode.parentNode.rowIndex;
     
     if(row !== id){
      tbl.outerHTML = "";
    }
    var updateBtn = document.getElementById('syncButton');
        updateBtn.removeAttribute('onclick", "Update()');
        updateBtn.setAttribute('onclick', 'deleteSyncBtn()');
   }
   }



  function deleteSyncBtn() {
    if(!confirm("Data Sync successfully ?")) return;
    
    var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
     if (this.readyState === 4 && this.status === 200) {
      console.log(this.responseText);
    }
  }
    var data = { "id" : id };
    console.log(data);
    var ArrayData = JSON.stringify(data);
    console.log(ArrayData);
    xmlhttp.open("POST", "delete.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/json")
    xmlhttp.send(ArrayData);
    var updateBtn = document.getElementById('syncButton');
    updateBtn.removeAttribute('onclick', 'deleteSyncBtn()');
    updateBtn.setAttribute('onclick', 'Update()');
    
}







   ///edit
  function editValue1(index) {
  return function () {
    var newData = this.parentNode.parentNode.querySelector('td:nth-child(2)');
    var name = newData.innerHTML;
    var inputV = document.getElementById('InputVal');
    inputV.value = name;
    document.getElementById('txt').innerHTML = "Update Customer Name";
    var update = document.getElementById('btn1');
    update.value = "Update";
    update.removeAttribute("onclick");
    update.removeEventListener('click', Save);
    update.addEventListener('click', handleUpdate);
    function handleUpdate() {
      var newName = inputV.value;                       
      jsonData[index].value = newName;
      localStorage.setItem('data', JSON.stringify(Array));
      document.getElementById("form").reset();
      newData.innerHTML = newName;
      var updateTxt = document.getElementById('txt').innerHTML = "Enter Your Name";
      update.value = "Add";
      update.removeEventListener('click', handleUpdate);
    }
  }
}

  function Update() {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      console.log(this.responseText);
    }
  }
  var ArrayData = JSON.stringify(DataArray);
  console.log(ArrayData);
  xmlhttp.open("POST", "update.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/json")
  xmlhttp.send(ArrayData);
  alert("Data Sync successfully")

}

//Add value ;
if (localStorage.getItem('data')) {
  Array = JSON.parse(localStorage.getItem('data'));
}
window.addEventListener("beforeunload", function () {
  Array = [];
  localStorage.removeItem('data');
});
Array = Array.concat(jsonData);

//save local data;
function Save() {
     var inputVal = document.getElementsByClassName('input');
     var inputValue = inputVal[0].value;
  if (inputValue==='') {
    const msg = document.getElementById('message');
    msg.innerHTML = "";
    const create = document.createElement('span');
    create.innerHTML = '*Please fill this field';
    msg.appendChild(create);
  } else{
    document.getElementById("message").style.display = "none";
    var id = generateUniqueId();
    var existingIndex = Array.findIndex(function (item) {
      return item.id === id;
    });
    if (existingIndex !== -1) {
      Array[existingIndex].value = inputValue;
    } else {
      Array.push({ id: id, value: inputValue });
    }
    localStorage.setItem('data', JSON.stringify(Array));
    document.getElementById("form").reset();
    CallTable();
  }
}

//Create dynamic table;
function CallTable() {
  var table = document.getElementById("Table");
  table.innerHTML = '';
  var newThead = document.createElement('thead');
  var newTheadRow = document.createElement('tr');
  var newTheadTh1 = document.createElement('th');
  var newTheadTh2 = document.createElement('th');
  var newTheadTh3 = document.createElement('th');
  newTheadTh1.innerHTML = 'Sr.No';
  newTheadTh2.innerHTML = 'Name';
  newTheadTh3.innerHTML = 'Option';
  newTheadRow.appendChild(newTheadTh1);
  newTheadRow.appendChild(newTheadTh2);
  newTheadRow.appendChild(newTheadTh3);
  newThead.appendChild(newTheadRow);
  table.appendChild(newThead);
  for (var i = 0; i < Array.length; i++) {
    var newTbody = document.createElement('tbody');
    table.appendChild(newTbody);
    var Row = document.createElement('tr');
    var Cell1 = document.createElement('td');
    var Cell2 = document.createElement('td');
    var Cell3 = document.createElement('td');
    var Button = document.createElement('button');
    Button.className = 'edit';
    Button.innerHTML = 'Edit';

    // Button.removeAttribute('onclick', 'post()');
    Button.setAttribute('onclick', 'updateLocalData()')
    var DeleteButton = document.createElement('button');
    DeleteButton.className = 'delBtn';
    DeleteButton.innerHTML = 'Delete';
    DeleteButton.addEventListener('click', deleteLocal(i));
    var val = Button.addEventListener('click', editValue(i));
    Cell1.innerHTML = i + 1;
    Cell2.innerHTML = Array[i].value;
    Cell3.appendChild(Button);
    Cell3.appendChild(DeleteButton);
    Row.appendChild(Cell1);
    Row.appendChild(Cell2);
    Row.appendChild(Cell3);
    newTbody.appendChild(Row);
  }
  SyncBtn()
}

//update local data;
function editValue(index) {
  return function () {
    var newData = this.parentNode.parentNode.querySelector('td:nth-child(2)');
    var name = newData.innerHTML;
    console.log(name);
    var inputV = document.getElementById('InputVal');
    inputV.value = name;
    document.getElementById('txt').innerHTML = "Update Customer Name";
    var update = document.getElementById('btn1');
    update.value = "Update";
    update.removeAttribute("onclick");
    update.setAttribute('onclick', 'updateLocalData()');
    update.removeEventListener('click', Save);
    update.addEventListener('click', handleUpdate);
    function handleUpdate() {
      var newName = inputV.value;
      Array[index].value = newName;
      localStorage.setItem('data', JSON.stringify(Array));
      document.getElementById("form").reset();
      newData.innerHTML = newName;
      var updateTxt = document.getElementById('txt').innerHTML = "Enter Your Name";
      update.value = "Add";
      update.removeEventListener('click', handleUpdate);
      update.addEventListener("click", Save);
    }
  }
}


function updateLocalData() {
  var syncEle = document.getElementById('syncInput');
  syncEle.removeAttribute('onclick', 'post()');
  syncEle.setAttribute('onclick', 'updateDataLocal()');
}

function updateDataLocal() {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      console.log(this.responseText);
    }
  }
  var ArrayData = JSON.stringify(DataArray);
  console.log(ArrayData);
  xmlhttp.open("POST", "update.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/json")
  xmlhttp.send(ArrayData);
  alert("Data Sync successfully");
  var syncEle = document.getElementById('syncInput');
  syncEle.removeAttribute('onclick', 'updateDataLocal()');
  syncEle.setAttribute('onclick', 'post()');

}



function deleteLocal(index){
  return function () {
   var arr = Array[index].id;
   if(!confirm("Are you sure you want to delete from localStorage ?")) return;
    var tbl = this.parentNode.parentNode.parentNode;
    var row = this.parentNode.parentNode.rowIndex;
    if(row !== arr){
      tbl.outerHTML = "";
      removeObjectWithId(Array, arr);
    }
  }
}

function removeObjectWithId(arr, id) {
  const objWithIdIndex = arr.findIndex((obj) => obj.id === id);
  if (objWithIdIndex > -1) {
    arr.splice(objWithIdIndex, 1);
  }
  localStorage.setItem('data', JSON.stringify(arr));
}

//generate unique id in a form of characters;
function generateUniqueId() {
  var id = '';
  var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  for (var i = 0; i < 6; i++) {
    id += characters.charAt(Math.floor(Math.random() * characters.length));
  }
  return id;
}

//Post local data to database;
function SyncBtn() {
  var syncEle = document.getElementById('syncBtn');
  syncEle.innerHTML = '';
  var elementInput = document.createElement('input');
  elementInput.type = 'button';
  elementInput.id = "syncInput"
  elementInput.value = "Sync-Records";
  elementInput.formMethod = 'post';
  elementInput.setAttribute('onclick', 'post()');
  syncEle.appendChild(elementInput);
}

function post() {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      console.log(this.responseText);
    }
  }
  Array = Array.filter(word => word.id.length >= 5);
  console.log(Array);
  var ArrayData = JSON.stringify(Array);
  xmlhttp.open("POST", "post.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/json")
  xmlhttp.send(ArrayData);
  alert("Data Sync successfully")
}
  </script>
</body>
</html>
