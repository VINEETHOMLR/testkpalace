<?php
    use inc\Root;
//echo "<pre>"; print_r($this->admin_services); die;
    ?>
<style>
.sidebar-wrapper{
    width: 260px;
}
#content{
    margin-left: 260px;
}
</style>
<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            
            <li class="menu">
                <a href="<?=BASEURL?>" id="dashboard" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            <?php if( array_intersect($this->admin_services, range(1,6)) || ($this->admin_role==1)) { ?> 

            <li class="menu">
                <a href="#adminNav" id="adminMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <span>Admin Management</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="adminNav" data-parent="#accordionExample">
                    <?php if( in_array(1, $this->admin_services) || ($this->admin_role==1) ){ ?> 
                    <li id="adminList">
                        <a href="<?=BASEURL;?>Admin/"> Admin List </a>
                    </li>
                    <?php }if( in_array(3, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="serviceNav">
                        <a href="<?=BASEURL;?>Service/"> Service Group </a>
                    </li>
                    <?php } if( in_array(2, $this->admin_services) || $this->admin_role==1){ ?>
                    <li id="subAdminNav">
                        <a href="<?=BASEURL;?>Admin/SubadminActivity/">Activity Log</a>
                    </li>
                    <?php } ?>
                </ul>
            </li>

            <?php }if( in_array(9, $this->admin_services) || in_array(10, $this->admin_services) || in_array(11, $this->admin_services) || in_array(12, $this->admin_services) || in_array(13, $this->admin_services) || in_array(14, $this->admin_services) || in_array(44, $this->admin_services) || ($this->admin_role==1)) { ?> 

            <li class="menu">
                <a href="#custNav" id="custMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Customer Management</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
               
                <ul class="collapse submenu list-unstyled" id="custNav" data-parent="#accordionExample">
                    <?php if( in_array(9,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="userList">
                        <a href="<?=BASEURL;?>Customer/Index/"> Customer List </a>
                    </li>
                    <?php } if( in_array(44,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="createcustomer">
                        <a href="<?=BASEURL;?>Customer/CreateCustomer/"> Create Customer </a>
                    </li>
                    <?php }if( in_array(51,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="customerProfileRequest">
                        <a href="<?=BASEURL;?>Customer/CustomerProfileRequest/"> Customer Profile Request </a>
                    </li>
                    <?php } if( in_array(14, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <!-- <li id="alcohol">
                        <a href="<?=BASEURL;?>Customer/CustomerAlcoholList/"> Customer Alcohol </a>
                    </li> -->
                    <!-- <li id="alcohol">
                        <a href="<?=BASEURL;?>Customer/UpdateAlcoholList/"> Update Balance </a>
                    </li> -->
                    <?php } if( in_array(13, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="gallery">
                        <a href="<?=BASEURL;?>Customer/Gallery"> Customer Gallery </a>
                    </li>
                    <?php } ?>
                </ul>
            </li>

            <?php }if( in_array(48, $this->admin_services) || in_array(49, $this->admin_services) || in_array(50, $this->admin_services) || ($this->admin_role==1)) { ?> 

            <li class="menu">
                <a href="#userNav" id="userMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>User Management</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
               
                <ul class="collapse submenu list-unstyled" id="userNav" data-parent="#accordionExample">
                    <?php if( in_array(48,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="usersList">
                        <a href="<?=BASEURL;?>User/Index/"> User List </a>
                    </li>
                    <?php } if( in_array(49,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="createuser">
                        <a href="<?=BASEURL;?>User/CreateUser/"> Create User </a>
                    </li>
                     <?php } if( in_array(50,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="UserServiceNav">
                        <a href="<?=BASEURL;?>UserService/">User Services</a>
                    </li>
                    <?php } ?>
                </ul>
            </li>

            <?php }if( in_array(15, $this->admin_services) || in_array(16, $this->admin_services) || in_array(59, $this->admin_services) || ($this->admin_role==1)) { ?> 

            <li class="menu">
                <a href="#AlcoholNav" id="AlcoholMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <span>Inventory Management</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                
                <ul class="collapse submenu list-unstyled" id="AlcoholNav" data-parent="#accordionExample">
                    
                    <?php if( in_array(15,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="SupplierList">
                        <a href="<?=BASEURL;?>Alcohol/SupplierList/"> Supplier List </a>
                    </li>
                    <?php } if( in_array(15,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="cat">
                        <a href="<?=BASEURL;?>Alcohol/ListCategory/"> Category </a>
                    </li>
                <?php } if( in_array(16, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="al_list">
                        <a href="<?=BASEURL;?>Alcohol/List"> Inventory list </a>
                    </li>
                    <?php } if( in_array(59, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="inventory_edit_list">
                        <a href="<?=BASEURL;?>Inventory/Index"> Edit Request</a>
                    </li>
                    <?php } if( in_array(71, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="inventory_transactions">
                        <a href="<?=BASEURL;?>Inventory/TransactionsList"> Inventory Transactions</a>
                    </li>
                    <?php } if( in_array(71, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="inventory_stock">
                        <a href="<?=BASEURL;?>Stock"> Stock</a>
                    </li>
                    <?php }  ?>
                </ul>
            </li>

           <?php }
           if( in_array(46, $this->admin_services) || in_array(47, $this->admin_services) || in_array(64, $this->admin_services) || in_array(67, $this->admin_services) || in_array(87, $this->admin_services) || ($this->admin_role==1)) { ?> 

            <li class="menu">
                <a href="#PurchaseNav" id="PurchaseMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <span>Sales</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
               
                <ul class="collapse submenu list-unstyled" id="PurchaseNav" data-parent="#accordionExample">
                    <?php if( in_array(46,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="create_purchase">
                        <a href="<?=BASEURL;?>Purchase/Create/"> Add Sales </a>
                    </li>
                <?php } if( in_array(47, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="customer_purchase">
                        <a href="<?=BASEURL;?>Purchase/List"> Customer Sales </a>
                    </li>
                    <?php } if( in_array(67, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="customer_alcohol">
                        <a href="<?=BASEURL;?>Purchase/CustomerAlcohol"> Customer Alcohol </a>
                    </li>
                    <?php } if( in_array(64, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="OrderRequestList">
                        <a href="<?=BASEURL;?>OrderRequest/Index"> Order Request </a>
                    </li>
                    <?php } if( in_array(87, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="FocReport">
                        <a href="<?=BASEURL;?>OrderRequest/getFocReport"> FOC Report </a>
                    </li>
                    <?php } ?>


                </ul>
            </li>
        <?php } if(in_array(68, $this->admin_services) || in_array(77, $this->admin_services) || in_array(95, $this->admin_services) || in_array(96, $this->admin_services) ||  ($this->admin_role==1)){?>


            <li class="menu">
                <a href="#RsvpNav" id="RsvpMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <span>Rsvp</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
               
                <ul class="collapse submenu list-unstyled" id="RsvpNav" data-parent="#accordionExample">
                    <?php if( in_array(68,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="RsvpList">
                        <a href="<?=BASEURL;?>Rsvp/Index/">Rsvp List</a>
                    </li>
                    <?php }?>
                     <?php if( in_array(77,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="mergeRoomMenu">
                        <a href="<?=BASEURL;?>Rsvp/MergeRoom/">Merge/Change Room</a>
                    </li>
                    <?php }?>

                    <?php if( in_array(95,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="changeHistoryMenu">
                        <a href="<?=BASEURL;?>Rsvp/ChangeHistory/">Change History</a>
                    </li>

                    <?php }?>
                    <?php if( in_array(96,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="mergeHistoryMenu">
                        <a href="<?=BASEURL;?>Rsvp/MergeHistory/">Merge History</a>
                    </li>

                    <?php }?>


                </ul>
            </li>



       <?php } if(in_array(14, $this->admin_services) || in_array(78, $this->admin_services) || in_array(79, $this->admin_services) || in_array(80, $this->admin_services) || in_array(81, $this->admin_services) || in_array(82, $this->admin_services) || in_array(86, $this->admin_services) || ($this->admin_role==1)){?>


            <li class="menu">
                <a href="#LeaveNav" id="LeaveMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <span>Leave Management</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
               
                <ul class="collapse submenu list-unstyled" id="LeaveNav" data-parent="#accordionExample">
                    <?php if( in_array(78,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="LeaveList">
                        <a href="<?=BASEURL;?>Leave/Index/">Leave Settings</a>
                    </li>
                    <?php }if( in_array(82,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="LeaveRequestList">
                        <a href="<?=BASEURL;?>LeaveRequest/Index/">Leave Request</a>
                    </li>
                <?php }if( in_array(86,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="StaffList">
                        <a href="<?=BASEURL;?>LeaveRequest/StaffList/">Staff List</a>
                    </li>
                <?php }?>
                </ul>
            </li>



       <?php }if( in_array(17, $this->admin_services) || in_array(18, $this->admin_services) || in_array(19, $this->admin_services) || ($this->admin_role==1)) { ?> 

            <li class="menu">
                <a href="#HistoryNav" id="HistoryMainMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <span>History</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
               
                <ul class="collapse submenu list-unstyled" id="HistoryNav" data-parent="#accordionExample">
                    <?php if( in_array(17,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="Cashcredit">
                        <a href="<?=BASEURL;?>Wallet/Cashcredit/">Cash Credit</a>
                    </li>
                <?php } if( in_array(18, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="Bonuscredit">
                        <a href="<?=BASEURL;?>Wallet/Bonuscredit"> Bonus Credit </a>
                    </li>
                    <?php } if( in_array(19, $this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="TotalSpend">
                        <a href="<?=BASEURL;?>Wallet/TotalSpend"> Total Spend </a>
                    </li>
                    <?php } ?>
                </ul>
            </li>

            <?php } if( in_array(21, $this->admin_services) || in_array(22, $this->admin_services) || in_array(20, $this->admin_services) || in_array(55, $this->admin_services) ||in_array(55, $this->admin_services)||in_array(63, $this->admin_services)||in_array(75, $this->admin_services)||in_array(83, $this->admin_services)||in_array(91, $this->admin_services)||in_array(94, $this->admin_services)|| ($this->admin_role==1)) { ?> 
               <li class="menu">
                    <a href="#settingsNav" id="settingsMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            <span>Settings</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="settingsNav" data-parent="#accordionExample">
                        <?php if( in_array(21, $this->admin_services) || $this->admin_role==1){ ?>
                        <li id="promotion">
                            <a href="<?=BASEURL;?>Promotion/Index/">Promotion </a>
                        </li>
                        <?php }if( in_array(22, $this->admin_services) || ($this->admin_role==1)) { ?>
                        <li id="package">
                            <a href="<?=BASEURL;?>Package/Index/">Package </a>
                        </li>
                         
                        <?php  } /*if( in_array(20, $this->admin_services) || ($this->admin_role==1)){*/ ?>
                        <!-- <li id="memoNav">
                        <a href="<?=BASEURL;?>Memo/Index/"> Memo </a>
                        </li> -->
             
                        <?php /*}*/ ?>

                        <?php if( in_array(55, $this->admin_services) || ($this->admin_role==1)) { ?>
                        <li id="focList">
                            <a href="<?=BASEURL;?>Foc/Index/">Foc Remarks </a>
                        </li>
                        <?php }?>

                    <?php if( in_array(75,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="createroom">
                        <a href="<?=BASEURL;?>Room/Index/">Room/Table</a>
                    </li>
                    <?php } ?>

                    <?php if( in_array(72,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="Hours">
                        <a href="<?=BASEURL;?>Hours/Index/">Hour Type</a>
                    </li>
                    <?php } ?>

                    <?php if( in_array(83,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="allergiesList">
                        <a href="<?=BASEURL;?>Allergies/Index/">Allergies</a>
                    </li>
                    <?php } ?>

                    <?php if( in_array(91,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="departmentsList">
                        <a href="<?=BASEURL;?>Departments/Index/">Departments</a>
                    </li>
                    <?php } ?>

                    <?php if( in_array(94,$this->admin_services) || ($this->admin_role==1)){ ?>
                    <li id="positionsList">
                        <a href="<?=BASEURL;?>Positions/Index/">Positions</a>
                    </li>
                    <?php } ?>

                    <li id="termsandConditions">
                        <a href="<?=BASEURL;?>TermsAndConditions/Index/">Terms & Conditions</a>
                    </li>

                    </ul>
                 
              </li>



            <?php }


            


            if( in_array(45, $this->admin_services) || ($this->admin_role==1)) { ?>


                
                
            <li class="menu">
                <a href="<?=BASEURL?>Language/" id="langmenu" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                        <span>Language Management</span>
                    </div>
                </a>
            </li>


            <?php } 

       
                       
             ?>


        </ul>
    </nav>
</div>
