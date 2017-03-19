# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = '2'
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box = 'debian/jessie64'
    config.vm.network 'private_network', ip: '192.168.31.95'
    config.vm.host_name = 'chess-db.dev'
    
    config.vm.synced_folder '.', '/srv/share', id: 'vagrant-share', :nfs => true

    config.vm.provision 'ansible_local' do |ansible|
        ansible.install_mode      = 'pip'
        ansible.version           = '2.2.1'
        ansible.provisioning_path = '/srv/share/ansible'
        ansible.playbook          = 'site.yml'
        ansible.inventory_path    = 'inventory/hosts'
        ansible.limit             = 'devbox'
    end
end
