<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category</label>
                                <select type="text" class="form-control form-select" id="productCategoryUpdate">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productNameUpdate">

                                <label class="form-label mt-2">Price</label>
                                <input type="text" class="form-control" id="productPriceUpdate">

                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnitUpdate">
                                <br/>
                                <img class="w-15" id="oldImg" src="{{asset('images/default.jpg')}}"/>
                                <br/>
                                <label class="form-label mt-2">Image</label>
                                <input oninput="oldImg.src=window.URL.createObjectURL(this.files[0])"  type="file" class="form-control" id="img_url">

                                <input type="text" class="d-none" id="updateID">
                                <input type="text" readonly class="d-nne"  id="oldImgFromDb">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="update()" id="update-btn" class="btn bg-gradient-success" >Update</button>
            </div>

        </div>
    </div>
</div>


<script>

    async function fillCategoryDropdown(){
        let res=await axios.get("/categoryList");
        console.log(res.data);
        res.data.forEach((item,index)=>{
            let option=`
                <option  value="${item["id"]}">${item["name"]}</option>
            `;
            $("#productCategoryUpdate").append(option);
        });
    }

   async function fillUpUpdateForm(id){
        document.getElementById("updateID").value=id;

        showLoader();
        await fillCategoryDropdown();
        let res= await axios.post("/productSingle",{id:id})
        hideLoader();

        document.getElementById("oldImgFromDb").value=res.data["img_url"];
        document.getElementById("productNameUpdate").value=res.data["name"];
        document.getElementById("productPriceUpdate").value=res.data["price"];
        document.getElementById("productUnitUpdate").value=res.data["unit"];
        document.getElementById("productCategoryUpdate").value=res.data["category_id"];
        document.getElementById("oldImg").src=res.data["img_url"];
   }

   async function update(){
        let productName = document.getElementById('productNameUpdate').value;
        let productPrice = document.getElementById('productPriceUpdate').value;
        let productUnit = document.getElementById('productUnitUpdate').value;
        let productCategory = document.getElementById('productCategoryUpdate').value;
        let updateID = document.getElementById('updateID').value;
        let oldImgFromDb=document.getElementById('oldImgFromDb').value;
        let img_url = document.getElementById('img_url').files[0];

        console.log(updateID,productName,productPrice,productUnit,productCategory,img_url,oldImgFromDb);
        if(productName.length===0){
            errorToast("Product Name Required !");
        }else if(productPrice.length===0){
            errorToast("Product Price Required !");
        }else if(productUnit.length===0){
            errorToast("Product Unit Required !");
        }else if(productCategory.length===0){
            errorToast("Product Category Required !");
        }else{
            document.getElementById("update-modal-close").click();
            let formData= new FormData();
            formData.append('img_url',img_url)
            formData.append("name",productName)
            formData.append("price",productPrice)
            formData.append("unit",productUnit)
            formData.append("category_id",productCategory)
            formData.append("oldImgFromDb",oldImgFromDb)
            const config={
                headers:{
                    "content-type" : "multipart/form-data"
                }
            }

            showLoader();
            let res=await axios.post("/productUpdate",formData,config);
            hideLoader();
            if(res.status===200 && res.data["status"]==='success'){
                document.getElementById("save-form").reset();
                await getList();
                successToast(res.data["message"]);
            }else{
                errorToast(res.data["message"]);
            }
        }
       

   }



</script>
