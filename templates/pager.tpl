<script>
    $(window).load(function() {
        var authkey = '{authkey}';
        $('#appnotes').on('show.bs.modal', function(e) {
            var link = $(e.relatedTarget);
            var appname = link.data('name');
            var app_id = link.data('id');
            //console.log('appid is '+app_id);
            $.get('index.php', {
                module: 'douglas',
                aop: 'get_comments',
                id: app_id
            }, function(data) {
                $('#admin-comments').val(data);
                $('.modal-header .modal-title').html('Comments: ' + appname);
            }).always(function() {
                $('#save-changes').unbind();
                $('#save-changes').click(function() {
                    var comments = $('#admin-comments').val();
                    $.post('index.php', {
                        module: 'douglas',
                        aop: 'post_comments',
                        comments: comments,
                        id: app_id,
                        authkey: authkey
                    }, function(data){
                        //console.log(data);
                    });
                });
            });
        });

    });
</script>
{menu}
<table class="table table-striped">
    <tr>
        <th></th>
        <th>{LAST_NAME_SORT} / Email</th>
        <th>{CREATED_DATE_SORT}</th>
        <th>Completed</th>
        <th>Phone</th>
    </tr>
    <!-- BEGIN listrows -->
    <tr>
        <td class="admin-icons" style="white-space: nowrap">{print} {email_references} <a style="cursor:pointer" data-toggle="modal" data-target="#appnotes" data-name="{first_name} {last_name}" data-id="{id}"><i class="fa fa-pencil"></i></a> {delete}</td>
        <td>{last_name}, {first_name} ({email})</td>
        <td>{created_date}</td>
        <td>{complete}</td>
        <td>{phone}</td>
    </tr>
    <!-- END listrows -->
</table>
<div style="text-align:center;margin:auto;">{TOTAL_ROWS}<br />
    {PAGE_LABEL} {PAGES}<br />
    {LIMIT_LABEL} {LIMITS}</div>
<div class="align-right">{SEARCH}</div>

<div class="modal fade" id="appnotes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body"><textarea style="height : 300px" class="form-control" name="admin-comments" id="admin-comments"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="save-changes" class="btn btn-primary" data-dismiss="modal">Save changes</button>
            </div>
        </div>
    </div>
</div>