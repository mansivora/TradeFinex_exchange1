@if ($paginator->lastPage() > 1)
<ul class="pagination pagination-sm man">
<!-- <li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <a href="{{ $paginator->url(1) }}">First</a>
    </li> -->
    <li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <a href="{{ $paginator->appends(array('min' => Request::get('min'),
    'max'=> Request::get('max')))->url($paginator->currentPage()-1) }}">«</a>
    </li>
    <?php

$total = $paginator->currentPage() + 7;
?>
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
    <?php
$start = $paginator->currentPage();
$status = "";
if ($start <= $i && $i <= $total) {
	$status = "block";
} else {
	$status = "hidden";
	if ($i >= ($paginator->lastPage() - 3) && ($paginator->lastPage() - 3) < $start) {

		$status = "block";
	} elseif (($start - 3) <= $i && ($start + 3) >= $i) {
		$status = "block";
	}

}

?>
        <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }} {{$status}}"  >
            <a href="{{ $paginator->appends(array('min' => Request::get('min'),
    'max'=> Request::get('max')))->url($i) }}">{{ $i }}</a>
        </li>

    @endfor
    <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <a href="{{ $paginator->appends(array('min' => Request::get('min'),
    'max'=> Request::get('max')))->url($paginator->currentPage()+1) }}" >»</a>
    </li>
    <!--  <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <a href="{{ $paginator->url($paginator->lastPage()) }}" >Last</a>
    </li> -->
</ul>
@endif
