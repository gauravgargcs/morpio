<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php $script = ""; ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            

        </div>
    </div>
</div>
<!-- end page title -->
<div class="d-lg-flex">
    <div class="chat-leftsidebar me-lg-4">
        <div class="">
            <div class="chat-leftsidebar-nav">          
                <div class="tab-content py-4">
                    <div class="tab-pane show active" id="chat">
                        <h5 class="font-size-14 mb-3"><?= lang('all_users') ?></h5>
                        <div data-simplebar style="max-height: 410px;">
                            <?php
                            $profile = profile();
                            if ($profile->role_id == 2) {
                                $where = array('role_id !=' => '2', 'activated' => '1');
                            } else {
                                $where = array('activated' => '1');
                            }
                            $all_user_info = get_result('tbl_users',$where);
                            if (!empty($all_user_info)): 
                            ?>
                            <ul class="list-unstyled chat-list" >
                                <?php
                                $now = time() - 60 * 10;
                                foreach ($all_user_info as $v_user) :
                                $account_info = $this->chat_model->check_by(array('user_id' => $v_user->user_id), 'tbl_account_details');
                                if (!empty($account_info) && $account_info->user_id != $this->session->userdata('user_id')) {
                                if ($v_user->role_id == 1) {
                                    $user = lang('admin');
                                } elseif ($v_user->role_id == 3) {
                                    $user = lang('staff');
                                } else {
                                    $user = lang('client');
                                }
                                $time = $v_user->online_time;
                                if ($time > $now) {
                                    $key='online';
                                } else {
                                    $key='offline';
                                }

                                ?>

                                <li class="<?php
                                    if ($v_user->user_id == $user_id) {
                                        echo "active";
                                    }
                                    ?>">
                                    <a href="<?php echo base_url(); ?>chat/conversations/<?php echo $v_user->user_id ?>">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 align-self-center me-3">
                                                <?php if ($key == 'online') { ?>
                                                <i class="mdi mdi-circle text-success font-size-10"></i>
                                                <?php } else { ?>
                                                <i class="mdi mdi-circle text-warning font-size-10"></i>
                                                <?php } ?>
                                            </div>
                                            <div class="flex-shrink-0 align-self-center me-3">
                                                <img class="rounded-circle avatar-xs" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= fullname($v_user->user_id) ?>" src="<?= base_url(staffImage($v_user->user_id)) ?>" alt="message user image"/>
                                            </div>

                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="text-truncate font-size-14 mb-1"><?= $account_info->fullname ?></h5>
                                                <p class="text-truncate mb-0"><?= $user ?></p>
                                            </div>
                                            <!-- <div class="font-size-11">05 min</div> -->
                                        </div>
                                    </a>
                                </li> 
                                <?php
                            };
                            endforeach; ?>                            
                            </ul>
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-100 user-chat">
        <div class="card">
            <div class="p-4 border-bottom ">
                <div class="row">
                    <?php
                    if (!empty($chats)) {
                        $chat_title = $chats->title;
                        $chat_id = 'conversation_chat_' . $chats->private_chat_id;
                    } else {
                        $chat_title = fullname($user_id);
                        $chat_id = null;
                    }
                    ?>
                    <div class="col-md-4 col-9">
                        <h5 class="font-size-15 mb-1"><?= $chat_title ?></h5>
                        <!-- <p class="text-muted mb-0"><i class="mdi mdi-circle text-success align-middle me-1"></i> Active now</p> -->
                    </div>
                    <div class="col-md-8 col-3">
                        <ul class="list-inline user-chat-nav text-end mb-0">
                            <li class="list-inline-item">
                                <button class="btn btn-light btn-rounded waves-effect start_chat" data-bs-toggle="tooltip" title="<?= lang('start') . ' ' . lang('chat') ?>" type="submit" data-user_id="<?= $user_id ?>"><i class="bx bx-send"></i></button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <div>
            <?php
            // get all message by private chat id
            $messages = array();
            if (!empty($all_messages)) {
                foreach ($all_messages as $message) {
                    array_push($messages, $message);
                }
                $messages = array_reverse($messages);
                $window_id = "conversation_chat_" . $chats->private_chat_id;
                $script .= '$("#' . $window_id . '").scrollTop($("#' . $window_id . '")[0].scrollHeight);';
                ?>
            <div class="chat-conversation p-3" data-simplebar style="max-height: 486px;">
                <ul class="list-unstyled mb-0 chat conversation_chat" id="<?= $id ?>">
                    <?php

                    foreach ($messages as $message) : ?>
                    <?php
                    if ($message->user_id == $this->session->userdata('user_id')) {
                        ?>
                    <li class="right">
                        <div class="conversation-list">
                            <div class="ctext-wrap">
                                <div class="conversation-name"><?= fullname($message->user_id) ?></div>
                                <p>
                                    <?php echo $message->message ?>
                                </p>
                                <p class="chat-time mb-0" title="<?php echo display_datetime($message->message_time); ?>"><i class="bx bx-time-five align-middle me-1"></i><?= time_ago($message->message_time); ?></p>
                            </div>
                        </div>
                    </li>
                    <?php } else { ?>
                    <li>
                        <div class="conversation-list">
                            <div class="ctext-wrap">
                                <div class="conversation-name"><?= fullname($message->user_id) ?></div>
                                <p>
                                    <?php echo $message->message ?>
                                </p>
                                <p class="chat-time mb-0" title="<?php echo display_datetime($message->message_time); ?>"><i class="bx bx-time-five align-middle me-1"></i><?= time_ago($message->message_time); ?></p>
                            </div>
                        </div>
                    </li>
                    <?php } ?>
                    <?php endforeach; ?>
                </ul>
                <?php } ?>
            </div>
            <div class="p-3 chat-input-section">
                <div class="row">
                    <div class="col-auto">
                        <button type="submit" data-user_id="<?= $user_id ?>" class="btn btn-primary btn-rounded chat-send w-md waves-effect waves-light start_chat"><span class="d-none d-sm-inline-block me-2"><?= lang('start') . ' ' . lang('chat') ?></span> <i class="mdi mdi-send"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        setInterval(function () {
            all_conversations(<?= $user_id ?>);
        }, interval_time);

        <?php echo $script ?>
    });
</script>
