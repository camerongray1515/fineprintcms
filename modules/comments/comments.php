<?php
class Comments extends Module {
    function TAG_comment_area()
    {
        $data['page_alias'] = $this->module->get_page_alias();
        $data['result'] = $this->FP->session->flashdata('result');

        $content = '';
        // Fetch all comments from the database and build up views
        $database_response = $this->FP->db->get_where('module_comments_comments', array('page_alias' => $data['page_alias']));
        foreach ($database_response->result() as $comment)
        {
            $content .= $this->module->render_view('comment', (array) $comment);
        }

        $content .= $this->module->render_view('comment_form', $data);

        return $content;
    }

    function FRONTEND_post()
    {
        $name = $this->FP->input->post('name', TRUE);
        $email = $this->FP->input->post('email', TRUE);
        $comment = $this->FP->input->post('comment', TRUE);

        // Filter all inputs
        $name = $this->module->escape_tags($name);
        $email = $this->module->escape_tags($email);
        $comment = $this->module->escape_tags($comment);

        $page_alias = $this->FP->input->post('page-alias', TRUE);
        $redirect_to = $this->FP->input->post('redirect-to', TRUE);

        $result['success'] = TRUE;
        $result['message'] = 'Your comment has been posted successfully!';

        // Validate inputs
        if (empty($name) || empty($email) || empty($comment) || empty($page_alias) || empty($redirect_to))
        {
            $result['success'] = FALSE;
            $result['message'] = 'All fields are required to post a comment!';
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $result['success'] = FALSE;
            $result['message'] = 'That email address does not appear to be valid!';
        }
        else
        {
            // Now add the comment
            $comment = array(
                'page_alias'    => $page_alias,
                'name'          => $name,
                'email'         => $email,
                'comment'       => $comment
            );

            $this->FP->db->insert('module_comments_comments', $comment);
        }


        $this->FP->session->set_flashdata('result', $result);

        redirect($redirect_to);
    }
}