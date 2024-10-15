
<ul class="nav flex-row">
    <li role=" presentation" class="nav-item  @if($active_tab == 1)active @endif ">
         <a class="nav-link" href="@if(isset($id)){{Route('ModulsControllerGetStep1').'/'.$id}} @else#@endif"><i class='fa fa-info'></i>
      Step 1 - Module Information</a>
    </li>
    <li role=" presentation" class="nav-item @if($active_tab == 2)active @endif">
      <a class="nav-link" href="@if(isset($id)){{Route('ModulsControllerGetStep2').'/'.$id}} @else#@endif"><i class='fa fa-table'></i> Step 2 - Data structure</a>>
    </li>
    <li role=" presentation" class="nav-item @if($active_tab == 3)active @endif">
      <a class="nav-link" href="@if(isset($id)){{Route('ModulsControllerGetStep3').'/'.$id}} @else#@endif"><i class='fa fa-table'></i> Step 3 - List Settings</a>
    </li>
    <li role=" presentation" class="nav-item @if($active_tab == 4)active @endif">
      <a class="nav-link" href="@if(isset($id)){{Route('ModulsControllerGetStep4').'/'.$id}} @else#@endif"><i class='fa fa-plus-square-o'></i> Step 4 - Form Settings</a>
    </li>
    <li role=" presentation" class="nav-item @if($active_tab == 5)active @endif">
      <a class="nav-link" href="@if(isset($id)){{Route('ModulsControllerGetStep5').'/'.$id}} @else#@endif"><i class='fa fa-wrench'></i> Step 5 - Configuration</a>
    </li>
</ul>