@extends('layout.sidenav-layout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <div class="row">
                        <div class="col-8">
                            <span class="text-bold text-dark">BILLED TO </span>
                            <p class="text-xs mx-0 my-1">Name:  <span id="CName"></span> </p>
                            <p class="text-xs mx-0 my-1">Email:  <span id="CEmail"></span></p>
                            <p class="text-xs mx-0 my-1">User ID:  <span id="CId"></span> </p>
                        </div>
                        <div class="col-4">
                            <img class="w-50" src="{{"images/logo.png"}}">
                            <p class="text-bold mx-0 my-1 text-dark">Invoice  </p>
                            <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }} </p>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                        <div class="col-12">
                            <table class="table w-100" id="invoiceTable">
                                <thead class="w-100">
                                <tr class="text-xs">
                                    <td>Name</td>
                                    <td>Qty</td>
                                    <td>Total</td>
                                    <td>Remove</td>
                                </tr>
                                </thead>
                                <tbody  class="w-100" id="invoiceList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                       <div class="col-12">
                           <p class="text-bold text-xs my-1 text-dark"> TOTAL: <i class="bi bi-currency-dollar"></i> <span id="total"></span></p>
                           <p class="text-bold text-xs my-2 text-dark"> PAYABLE: <i class="bi bi-currency-dollar"></i>  <span id="payable"></span></p>
                           <p class="text-bold text-xs my-1 text-dark"> VAT(5%): <i class="bi bi-currency-dollar"></i>  <span id="vat"></span></p>
                           <p class="text-bold text-xs my-1 text-dark"> Discount: <i class="bi bi-currency-dollar"></i>  <span id="discount"></span></p>
                           <span class="text-xxs">Discount(%):</span>
                           <input   value="0" type="text"  onkeyup="DiscountChange()" class="form-control w-40 " id="discountP"/>
                           <p>
                              <button onclick="createInvoice()" class="btn  my-3 bg-gradient-primary w-40">Confirm</button>
                           </p>
                       </div>
                        <div class="col-12 p-2">

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table  w-100" id="productTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Product</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="productList">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table table-sm w-100" id="customerTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Customer</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="customerList">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>




    <div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
                </div>
                <div class="modal-body">
                    <form id="add-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                   
                                        <label class="form-label">Product ID *</label>
                                        <input type="text" class="form-control" id="PId">
                                        <label class="form-label mt-2">Product Name *</label>
                                        <input type="text" class="form-control" id="PName">
                                        <label class="form-label mt-2">Product Price *</label>
                                        <input type="text" class="form-control" id="PPrice">
                                        <label class="form-label mt-2">Product Qty *</label>
                                        <input type="text" class="form-control" id="PQty">
                                   
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="add()" id="save-btn" class="btn bg-gradient-success" >Add</button>
                </div>
            </div>
        </div>
    </div>


    


    <script>
       (async ()=>{
          showLoader();
          await  CustomerList();
          await ProductList();
          hideLoader();
        })()

        let InvoiceItemList=[];

         function DiscountChange(){
            CalculateGrandTotal();
         }

         function  CalculateGrandTotal(){
            let Total=0;
            let Vat=0;
            let Payable=0;
            let Discount=0;
            let discountPercentage=(parseFloat(document.getElementById('discountP').value));

            
            InvoiceItemList.forEach(function (item){
                Total=parseFloat(Total)+parseFloat(item["sale_price"]);
            });

            if(discountPercentage>0){
                Discount= parseFloat(((Total*discountPercentage)/100).toFixed(2));
           
                Total=parseFloat((Total-Discount).toFixed(2));
             
                Vat= parseFloat(((Total*5)/100).toFixed(2));
               
                Payable=parseFloat((Total+Vat).toFixed(2));
            }
            else{
                Vat= parseFloat(((Total*5)/100).toFixed(2));
            
                Payable=parseFloat((Total+Vat).toFixed(2));
                // console.log(Payable)
            }
             
           
            // console.log(Total,Vat,Discount,Payable);
            document.getElementById('total').innerText=Total;
            document.getElementById('vat').innerText=Vat;
            document.getElementById('discount').innerText=Discount;
            document.getElementById('payable').innerText=Payable;
         }
        function removeItem (index){
            InvoiceItemList.splice(index,1);
            ShowInvoiceItem();
        }
        function ShowInvoiceItem(){
            let invoiceList=$("#invoiceList");
            invoiceList.empty();

            InvoiceItemList.forEach(function(item,index){
                console.log(item);
                let row=`
                    
                    <tr>
                        <td>
                            ${item["product_name"]}
                        </td>
                        <td>
                            ${item["qty"]}
                        </td>
                        <td>
                            ${item["sale_price"]}
                        </td>
                        <td>
                            <a data-index="${index}" class="btn remove text-xxs px-2 py-1  btn-sm m-0">
                                Remove
                            </a>
                        </td>
                    </tr>
                
                `;
                invoiceList.append(row)
            });
            CalculateGrandTotal();
            $(".remove").on("click",function(){
                let index=$(this).data("index");
               removeItem(index);
                
            })

        }
        function add(){
            let PId= document.getElementById("PId").value;
            let PName= document.getElementById("PName").value;
            let PPrice=document.getElementById("PPrice").value;
            let PQty=document.getElementById("PQty").value;
            let PTotalPrice= (parseFloat(PQty)*parseFloat(PPrice)).toFixed(2);

            if(PQty.length<=0){
                errorToast("Quantity Required !");
            }else if(PId.length<=0){
                errorToast("Product id Required !");
            }else if(PName.length<=0){
                errorToast("Product Name Required !");
            }else if(PPrice.length<=0){
                errorToast("Product Price Required !");
            }else{
                let item={
                    product_name:PName,
                    product_id:PId,
                    qty:PQty,
                    sale_price:PTotalPrice
                }
                InvoiceItemList.push(item);
                $('#create-modal').modal('hide')
               ShowInvoiceItem();
            }
        }
        function addModal(id,name,price) {
            $("#PId").val(id)
            $("#PName").val(name)
            $("#PPrice").val(price)
            $('#create-modal').modal('show')
        }
        async function CustomerList(){
            let res= await axios.get("/customerList");
            // console.log(res);

            let customerTable=$("#customerTable");
            let customerList=$("#customerList");

             customerTable.DataTable().destroy();
             customerList.empty();
             res.data.forEach(function (item,index) {
          
                 let row=`
                    <tr>
                        <td>${item['name']}</td>
                        <td>
                            <a data-name="${item['name']}" data-email="${item['email']}" data-id="${item['id']}" class="btn btn-outline-dark addCustomer  text-xxs px-2 py-1  btn-sm m-0">Add</a>
                        </td>
                    </tr>

                    
                 
                 `;
                 customerList.append(row)
             });

             $(".addCustomer").on("click",async function(){
                let CName=$(this).data("name");
                let CEmail=$(this).data("email");
                let CId=$(this).data("id");
                $("#CName").text(CName)
                $("#CEmail").text(CEmail)
                $("#CId").text(CId)
             })
            new DataTable('#customerTable',{
                order:[[0,'desc']],
                // scrollCollapse: false,
                // info: false,
                // lengthChange: false
                lengthMenu:[5,10,15,20,25]
            });
        }

        async function ProductList(){
            let res= await axios.get("/productList");

            let productTable=$("#productTable");

            let productList=$("#productList");

            productTable.DataTable().destroy();
            productList.empty();


            res.data.forEach(function(item,index){
                let row=`

                    <tr>
                        <td>${item["name"]} ($ ${item["price"]})</td>
                        <td>
                            <a data-name="${item['name']}" data-price="${item['price']}" data-id="${item['id']}" class="btn btn-outline-dark addProduct  text-xxs px-2 py-1  btn-sm m-0">Add</a>
                        </td>
                    </tr>
                
                `;

                productList.append(row)
            });


            $(".addProduct").on("click", function(){
                let PName=$(this).data("name");
                let PPrice=$(this).data("price");
                let PId=$(this).data("id");
                addModal(PId,PName,PPrice)
            });

            new DataTable('#productTable',{
                order:[[0,'desc']],
                // scrollCollapse: false,
                // info: false,
                // lengthChange: false
                lengthMenu:[5,10,15,20,25]
            });
        }
        
        
        async function createInvoice(){
            let total=document.getElementById("total").innerText;
            let vat=document.getElementById("vat").innerText;
            let payable=document.getElementById("payable").innerText;
            let discount=document.getElementById("discount").innerText;
            let customer_id=document.getElementById("CId").innerText;

            showLoader();
            let res=await axios.post("/createInvoice",{total:total,vat:vat,payable:payable,discount:discount,products:InvoiceItemList,customer_id:customer_id});
            hideLoader();

            if(res.data["status"]==="success" && res.status===200){
                successToast(res.data["message"]);
                InvoiceItemList=[];
                ShowInvoiceItem();
                document.getElementById("total").innerText="0";
                window.location.href="/invoicePage";
                
            }else{
                errorToast(res.data["message"]);
            }
        }
        

    </script>




@endsection
