@extends('layouts.admin_master')

@section('content')

    {{-- data table --}}
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <a href="" id="add" class="general-action add"><i class="fa fa-plus-circle"></i></a>
                        <table id="ppk-table" class="table table-striped">
                            <thead>
                            <tr>
                                <th class="no">#</th>
                                <th>PPK Name</th>
                                <th>Employee Name</th>
                                <th>Year</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- add modal --}}
    <div id="ppk-modal" class="modal fade" data-backdrop="static" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="" id="ppk-form" class="form-horizontal">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" arial-lable="Close" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert nonmodalalert redalert" id="error"></div>
                    <div class="form-group">
                        <label for="ppkid" class="col-md-3 control-label">PPK Name</label>
                        <div class="col-md-9">
                            <input id="ppkid" type="text" name="ppkid" class="form-control" placeholder="eg. PPK01">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-md-3 control-label">Employee Name</label>
                        <div class="col-md-9">
                            <input id="name" type="text" name="name" class="form-control" placeholder="eg. Patar Ebenezer Siahaan">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="year" class="col-md-3 control-label">Year</label>
                        <div class="col-md-9">
                            <input id="year" type="text" name="year" class="form-control" placeholder="eg. 2017">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="add-btn" data-loading-text="<i class='fa fa-spinner fa-spin '></i>" class="btn pull-right btn-primary btn-flat">Add</button>
                    <button type="button" id="update-btn" data-loading-text="<i class='fa fa-spinner fa-spin '></i>" class="btn pull-right btn-primary btn-flat">Update</button>
                    <button id="cancel-btn" data-dismiss="modal" arial-lable="Close" class="btn pull-left btn-danger btn-flat">Cancel</button>
                </div>
            </form>

        </div>
    </div>

    {{-- success modal --}}
    <div id="successmodal" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content modalalert greenalert">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span><i class="fa fa-check-square"></i></span><span id="successmessage"></span>
                </div>
            </div>
        </div>
    </div>

@endsection