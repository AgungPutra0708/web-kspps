{{-- Content SHU --}}
<div class="scrollable-content"> <!-- Make the section scrollable -->
    {{-- Content Header --}}
    <!--<div class="d-flex justify-content-center mb-1 mt-2">-->
    <!--    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">-->
    <!--        <div class="card border-0">-->
    <!--            <div class="card-body py-1">-->
    <!--                <div class="row">-->
    <!--                    <div class="col-lg-12 col-12 align-content-center">-->
    <!--                        <h3 class="font-weight-bold m-0">Market BMT</h3>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    {{-- Content Header End --}}


    <div class="d-flex justify-content-center mb-1 mt-2">
        <div class="col-12">
            <embed src="{{ $dataProfile && $dataProfile->link_market ? $dataProfile->link_market : '#' }}"
                width="100%" height="500px">
        </div>
    </div>
</div>
{{-- End Content SHU --}}
