<div role="dialog" id="{{$modal_id}}" class="modal fade " style="display: none;">
   {!! Form::model($ticket, array('url' => route('postEditTicket', array('ticket_id' => $ticket->id, 'event_id' => $event->id)), 'class' => 'ajax ')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-ticket"></i>
                    Edit Ticket: <i>{{{$ticket->title}}}</i></h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('title', 'Ticket Title', array('class'=>'control-label required')) !!}
                            {!!  Form::text('title', Input::old('title'),
                                        array(
                                        'class'=>'form-control',
                                        'placeholder'=>'E.g: General Admission'
                                        ))  !!}
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('price', 'Ticket Price', array('class'=>'control-label required')) !!}
                                    {!!  Form::text('price', Input::old('price'),
                                                array(
                                                'class'=>'form-control',
                                                 'placeholder'=>'E.g: 25.99'
                                                ))  !!}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('quantity_available', 'Quantity Available', array('class'=>' control-label')) !!}
                                    {!!  Form::text('quantity_available', Input::old('quantity_available'),
                                                array(
                                                'class'=>'form-control',
                                                'placeholder'=>'E.g: 100 (Leave blank for unlimited)'
                                                )
                                                )  !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group more-options">
                            {!! Form::label('description', 'Ticket Description', array('class'=>'control-label')) !!}

                            {!!  Form::text('description', Input::old('description'),
                                        array(
                                        'class'=>'form-control'
                                        ))  !!}
                        </div>

                        <div class="row more-options">
                            <div class="col-sm-6">
                                <div class="form-group">
                                   {!! Form::label('start_sale_date', 'Start Sale On', array('class'=>' control-label')) !!}

                                    {!!  Form::text('start_sale_date', $ticket->getFormatedDate('start_sale_date'),
                                                        [
                                                    'class'=>'form-control start hasDatepicker ',
                                                    'data-field'=>'datetime',
                                                    'data-startend'=>'start',
                                                    'data-startendelem'=>'.end',
                                                    'readonly'=>''

                                                ])  !!}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!!  Form::label('end_sale_date', 'End Sale On',
                                                [
                                            'class'=>' control-label '
                                        ])  !!}
                                    {!!  Form::text('end_sale_date', $ticket->getFormatedDate('end_sale_date'),
                                                [
                                            'class'=>'form-control end hasDatepicker ',
                                            'data-field'=>'datetime',
                                            'data-startend'=>'end',
                                            'data-startendelem'=>'.start',
                                            'readonly'=>''
                                        ])  !!}
                                </div>
                            </div>
                        </div>

                        <div class="row more-options">
                            <div class="col-md-6">
                                <div class="form-group">
                                   {!! Form::label('min_per_person', 'Minimum Tickets Per Order', array('class'=>' control-label')) !!}
                                   {!! Form::selectRange('min_per_person', 1, 100, Input::old('min_per_person'), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                   {!! Form::label('max_per_person', 'Maximum Tickets Per Order', array('class'=>' control-label')) !!}
                                   {!! Form::selectRange('max_per_person', 1, 100, Input::old('max_per_person'), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <a href="javascript:void(0);" class="show-more-options">
                            More Options
                        </a>
                    </div>

                </div>

            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::button('Close', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit('Save Ticket', array('class'=>"btn btn-success")) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>
