<?php
$frontend = $this->uri->segment(1);
$mid = my_id();
if (!empty($mid) && $frontend != 'frontend') { ?>
    <div class="chat_frame">
        <?php include_once 'skote_assets/plugins/chat/chat.php'; ?>
        <button type="button" class="btn btn-round custom-bg" id="open_chat_list"><span
                class="fa fa-comments"></span></button>
        <div class="card" id="chat_list">
            <div class="card-body" id="">
                <div class="pull-right chat-icon float-end">
                    <!--                    <i data-bs-toggle="tooltip" data-bs-placement="top" title="-->
                    <? //= lang('add_more_to_chat') ?><!--"-->
                    <!--                       class="fa fa-plus" aria-hidden="true"></i>-->
                    <!--                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="-->
                    <? //= lang('settings') ?><!--"-->
                    <!--                       href=""> <i class="fa fa-cog" aria-hidden="true"></i></a>-->
                    <i id="close_chat_list" class="fa fa-times" aria-hidden="true"></i>
                </div>
                <h5 class="card-title font-size-14 mb-3"><?= lang('users') . ' ' . lang('list') ?></h5>

                <div data-simplebar style="max-height: 410px;">
                    <ul class="list-unstyled chat-list" >
                        <li>
                        <?php
                        $users = $this->admin_model->get_online_users();
                        if (!empty($users)) {
                            foreach ($users as $key => $v_users) {
                                if (!empty($v_users)) {
                                    foreach ($v_users as $v_user) {
                                        $designation=designation($v_user->user_id);
                                        if ($v_user->role_id == 1) {
                                            $user = lang('admin');
                                        } elseif ($v_user->role_id == 3) {
                                            $user = lang('staff');
                                        } else {
                                            $user = lang('client');
                                        }

                                    ?>
                                    <!-- START User status-->
                                    <a href="#"  data-user_id="<?= $v_user->user_id ?>" class="media-box p pb-sm pt-sm bb mt0 start_chat">
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
                                                <h5 class="text-truncate font-size-14 mb-1"><?= fullname($v_user->user_id) ?></h5>
                                                <p class="text-truncate mb-0">
                                                    <?php echo $user; if($designation!="-"){ echo "(".$designation.")"; } ?>        
                                                </p>
                                            </div>
                                            <div class="font-size-11">
                                                <?php
                                                if(!empty($v_user->online_time)){
                                                    echo time_ago($v_user->online_time);
                                                }else{
                                                    echo lang('never');
                                                }?>
                                                
                                            </div>
                                        </div>
                                    </a>
                                    <?php
                                }
                            }
                            }
                        } ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="chat_box"></div>
        <audio id="chat-tune" controls="">
            <source src="<?= base_url() ?>skote_assets/plugins/chat/chat_tune.mp3" type="audio/mpeg">
        </audio>
    </div>
    <!--End live_chat_section-->
<?php } ?>
