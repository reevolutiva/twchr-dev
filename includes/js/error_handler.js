function twchr_error_oauth(){
    alert('Oauth Token invalid');
    alert('You will be redirected to another page to renew your OAuth token');
    const url = twchr_admin_url+'edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true';
    location.href = url;
}