# -*- mode: ruby -*-
# vi: set ft=ruby :

boxes = [{
    :name => :dev,
    :host => 'chess-deb-dev.foo',
    :ip   => '192.168.31.95',
},{
    :name => :jenkins,
    :host => 'chess-deb-jenkins.foo',
    :ip   => '192.168.31.96',
},{
    :name => :stage,
    :host => 'chess-deb-stage.foo',
    :ip   => '192.168.31.97',
},{
    :name => :live,
    :host => 'chess-deb-live.foo',
    :ip   => '192.168.31.98',
}]

VAGRANTFILE_API_VERSION = '2'
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    boxes.each do |opts|
        config.vm.define opts[:name], autostart: false do |config|
            config.vm.box = 'geerlingguy/ubuntu1604'
            config.vm.network 'private_network', ip: opts[:ip]
            config.vm.host_name = opts[:host]
            
            config.vm.synced_folder '.', '/srv/share', id: 'vagrant-share', :nfs => true

            config.vm.provision 'ansible_local' do |ansible|
                ansible.provisioning_path = '/srv/share/ansible'
                ansible.playbook          = 'provision.yml'
                ansible.galaxy_role_file  = 'requirements.yml'
                ansible.galaxy_roles_path = 'galaxy_roles'
            end
        end
    end
end
