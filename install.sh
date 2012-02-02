#!/bin/bash
prefix=~/.lint
app_dir=$(readlink -f -- "$(dirname -- "$0")");
bin_dir="${app_dir}/bin";
conf_dir="${app_dir}/conf";
libs_dir="${app_dir}/libs";
modules_dir="${app_dir}/modules";

function usage(){
    echo "./install.sh -g git_base_address"
    echo "eg:"
    echo "./install.sh -g /home/zhouliang.zl/dev/apiproxy"
    exit 0;
}

while getopts p:g:h options;do
    case $options in
        g)    git=${OPTARG};;
        p)    prefix=${OPTARG};;
        h)   usage;;
        \?)   usage;;
    esac
done

if [ -z ${git} ]; then
    echo "git_base_address[$git] is not right"
    exit 1;
fi
if [ ! -e ${git} ]; then
    echo "git_base_address[$git] is not right"
    exit 1;
fi

prefix=`echo ${prefix} | sed "s/\/[ \t]*$//"`
git=`echo ${git} | sed "s/\/[ \t]*$//"`

git_hook=${git}/.git/hooks

if [ ! -e ${git_hook} ]; then
    echo "git_base_address[$git] can not find hook"
    exit 1;
fi


mkdir -p ${prefix};
cd ${prefix};
if [ -e ${prefix}/conf ]; then
    if [ -e ${prefix}/conf_bak ]; then
        rm -rf ${prefix}/conf_bak
    fi
    mv ${prefix}/conf ${prefix}/conf_bak
fi
cp -r ${bin_dir} ./
cp -r ${conf_dir} ./
cp -r ${modules_dir} ./
cp -r ${libs_dir} ./
cp ${app_dir}/pre-commit ${git}/.git/hooks/pre-commit
cd - > /dev/null;



